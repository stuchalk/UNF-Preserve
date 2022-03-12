<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the preserve table
 * Version: 1.0 (02/21/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class Preserve extends AppModel
{
	var $useTable=false;
    var $path='/Volumes/data/phptemp/';
    var $bin='/Volumes/data/bin/';

    /**
     * Inventory stats (plant, bird, insect, etc. lists...)
     * @return array|null
     */
	public function inventories()
	{
		$db=ConnectionManager::getDataSource('default');
		$data=$db->listSources();
		$exclude=['assets','photos','streams','webpages','websites'];
		foreach($data as $key=>$table) {
			if(in_array($table,$exclude)) { unset($data[$key]); }
		}
		return $data;
	}

    /**
     * Generates meta for and then uploads an image if a species
     * @param $type
     * @param $species
     * @param $upload
     * @param $col
     * @param $pcount
     * @return mixed
     * @throws
     */
    public function addimage($type,$species,$upload,$col,$pcount)
    {
        $Fobject = ClassRegistry::init('Fobject');
        $Repo = ClassRegistry::init('Repository');
        $Photo = ClassRegistry::init('Photo');

        // Initalize variables
        $streams=$rels=[];

        // Generate the pid
        if(!empty($query['pid'])) {
            $pid = $query['pid']; unset($query['pid']);
        } else {
            $ns=Configure::read('jaf.pidns');
            $pid=$ns.":1"; //Check to make sure that the pid next comes up with does not already exist
            while(!$Fobject->isNotObject($pid)) {
                list($pid)=$Repo->next('1',$ns);
            }
        }

        // Define the object metadata - $pcount comes from image upload form
        $meta=['label'=>$species['cname']." Photo ".$pcount,'ownerId'=>'UNF Environmental Center'];

        // Create DC metadata - description, format, creator, rights, type
        $dc=$this->image_dc($upload);

        // Move uploaded file
        $tempfile=$this->path.str_replace(" ","_",$upload['name']);
        move_uploaded_file($upload['tmp_name'],$tempfile);chmod($tempfile, 0777);
        $upload['tmp_name']=$tempfile;

        // Add a new stream for the original upload file
        $q=['dsLabel'=>"Original file",'checksumType'=>'DISABLED','logMessage'=>'Added original file'];
        $streams[]=['dsID'=>'Original','query'=>$q,'source'=>'Upload','upload'=>$upload];

        // Create EXIF file and metadata stream - from image file if exists
        $streams[]=$this->image_exif($upload);

        // Clean EXIF data and add watermark image
        $this->image_prep($upload);
        $upload['tmp_name']=$this->path."wm.jpg"; // Changed...

        // Create KML file and metadata stream - from EXIF data
        $streams[]=$this->image_kml($species);

        // Create Source file image with watermark
        $q=['dsLabel'=>"Source file",'checksumType'=>'DISABLED','logMessage'=>'Added source file '.$upload['name']];
        $streams[]=['dsID'=>'Source','query'=>$q,'source'=>'Upload','upload'=>$upload];

        // Create THUMB file from Source file
        $streams[]=$this->image_thumb($upload);

        // Create relationships to go into RELS-EXT stream - relationships to the collection and mammal
        if($col!="") { $rels[]=['predicate'=>'isItemOf','object'=>$col,'literal'=>'']; }
        if(isset($species['id'])):  $rels[]=['predicate'=>'isPartOf','object'=>'','literal'=>$type.':'.$species['id']];
        else:                       $rels[]=['predicate'=>'isPartOf','object'=>'','literal'=>$type]; // For photo groups
        endif;

        // Create item
        $data=$Fobject->add($pid,$meta,$dc,$streams,$rels);

        // Create entry in photos table
        if(!isset($species['id']))
        {
            $Photo->create();
            $Photo->save(['Photo'=>['label'=>$type.$pcount,'pid'=>$pid,'updated'=>date(DATE_ATOM)]]);
        }

        // Delete temp files
        if(file_exists($tempfile))					{ unlink($tempfile); }
        if(file_exists($this->path.'exif.xml'))		{ unlink($this->path.'exif.xml'); }
        if(file_exists($this->path.'marker.kml'))	{ unlink($this->path.'marker.kml'); }
        if(file_exists($this->path.'thumb.jpg'))	{ unlink($this->path.'thumb.jpg'); }
        if(file_exists($this->path.'wm.jpg'))		{ unlink($this->path.'wm.jpg'); }

        return $data;
    }

    /**
     * Makes an inventories KML file from the items in the collection
     * @param $col
     * @return mixed
     */
	public function col_kml($col)
	{
		// Get all the photos currently in the collection
		$Service = ClassRegistry::init('Service');
		$query="select ?pid where { ?pid <info:jaffedora/isItemOf> <info:fedora/".$col.">. ?pid <info:fedora/fedora-system:def/view#disseminates> ?stream. filter regex(str(?stream),'KML') }";
		$results=$Service->risearch($query);
		// Make KML file
		$kmlfile=Configure::read('kml.empty');
		$places="";
		$Dstream = ClassRegistry::init('Datastream');
        foreach($results as $result)
		{
			$kml=$Dstream->content($result['pid'],'KML',[],'raw');
			$place=$kml['content'];
			if(stristr($kml['content'],"<coordinates>0"))
			{
				$place=preg_replace("/<coordinates>.*<\/coordinates>/","<coordinates>-81.511829,30.26599,0</coordinates>",$place);
				$query=['query'=>['controlGroup'=>'X'],'source'=>'Text','text'=>$place];
				$Dstream->update($result['pid'],'KML',$query);
			}
			$place=trim(str_replace(['<kml xmlns="http://www.opengis.net/kml/2.2">','</kml>'],"",$place));
			$places.=$place;
		}
		$file=str_replace("**",$places,$kmlfile);
		// Save KML file
		$query=['source'=>'Text','text'=>$file,'query'=>['controlGroup'=>'X','mimeType'=>'application/vnd.google-earth.kml+xml']];
		$data=$Dstream->update($col,'KML',$query,'array');
		return $data;
	}

    /**
     * Create the dc stream metadata for an uploaded image
     * @param $image
     * @return array
     */
	public function image_dc($image)
	{
		$dc=[];
		($image['creator']=="") ? $dc['creator']='Justin Lemmons' : $dc['creator']=$image['creator'];
		$dc['format']=$image['type'];
		list($type,)=explode("/",$dc['format']);
		$dc['type']=ucfirst($type);
		$dc['language']='en-US';
		$dc['date']=date(DATE_ATOM);
		$dc['rights']='Copyright © '.date("Y").' UNF and the UNF Environmental Center';
		return $dc;
	}

    /**
     * Create the exif stream file and metadata for an uploaded image
     * @param $image
     * @return array
     */
	public function image_exif($image)
	{
		clearstatcache(); // Clears previous size of exif.xml (actually all files)
		$exiffile=$this->path."exif.xml";
        // -d is debug and means it still works if there is an error in the exif data
        exec($this->bin."exif -x ".$image['tmp_name']." > ".$exiffile,$execresult);
        if(filesize($exiffile)==0) // No EXIF in image file so write default location to file
		{
			$text="<exif>
				<Image_Description>No EXIF in file so this is default set.</Image_Description>
				<North_or_South_Latitude>N</North_or_South_Latitude>
				<Latitude>30, 15, 57.5634</Latitude>
				<East_or_West_Longitude>W</East_or_West_Longitude>
				<Longitude>81, 30, 42.5844</Longitude>
				<Altitude>0.0</Altitude>
			</exif>";
			$fp = fopen($exiffile,'w');
			fwrite($fp,$text);
			fclose($fp);
		}
		clearstatcache(); // Clears previous size of exif.xml (actually all files)

        // Remove extra spaces from exif data
        $exiftemp=file_get_contents($exiffile);
        $exiftemp=preg_replace("/[ ][ ]+/","",$exiftemp);
        $ef=fopen($exiffile,"w");
        fwrite($ef,$exiftemp);
        fclose($ef);

        $q=['dsLabel'=>"EXIF Data",'checksumType'=>'DISABLED','logMessage'=>'Added EXIF file'];
		$u=['name'=>'exif.xml','type'=>'text/xml','tmp_name'=>$exiffile,'error'=>'0','size'=>filesize($exiffile)];
		return ['dsID'=>'EXIF','query'=>$q,'source'=>'Upload','upload'=>$u];
	}

    /**
     * Create the kml stream file and metadata for an uploaded image (from existing exif.xml file)
     * @param $species
     * @return array
     */
	public function image_kml($species)
	{
		clearstatcache(); // Clears previous size of marker.kml (actually all files)
		$Fobject = ClassRegistry::init('Fobject');
		$kmlfile=$this->path.'marker.kml';
		$file=simplexml_load_file($this->path.'exif.xml');
		$temp=$file->xpath('/exif');
		$array=json_decode(json_encode($temp),true);
		$exif=$array[0];
		$kml=simplexml_load_file($this->path.'emptymarker.kml');
		$kml->Placemark[0]->name[0]=$species['cname'];
		$kml->Placemark[0]->description[0]="<i>".$species['sname']."</i>";
		$lat='30.26599';$long='-81.511829';$alt='0.0'; // Defaults if the image does not have GPS data in EXIF (location is the John Golden Pavilion)
		if(isset($exif['Latitude']))
		{
			$lat=trim(str_replace(" ","",$exif['Latitude']));
			list($d,$m,$s)=explode(",",$lat);
			$lat=$Fobject->dms2dec($d,$m,$s);
			if($exif['North_or_South_Latitude']=="S") { $lat=$lat*-1; }
		}
		if(isset($exif['Longitude']))
		{
			$long=trim(str_replace(" ","",$exif['Longitude']));
			list($d,$m,$s)=explode(",",$long);
			$long=$Fobject->dms2dec($d,$m,$s);
			if($exif['East_or_West_Longitude']=="W") { $long=$long*-1; }
		}
		if(isset($exif['Altitude'])) { $alt=$exif['Altitude']; }
		$kml->Placemark[0]->Point[0]->coordinates=$long.",".$lat.",".$alt;
		$handle=fopen($kmlfile,'w');
		fwrite($handle,$kml->asXML());
		fclose($handle);
		$q=['dsLabel'=>"OGC/Google KML(GPS) Data",'checksumType'=>'DISABLED','logMessage'=>'Added KML file'];
		$u=['name'=>'marker.kml','type'=>'application/vnd.google-earth.kml+xml','tmp_name'=>$kmlfile,'error'=>'0','size'=>filesize($kmlfile)];
		return ['dsID'=>'KML','query'=>$q,'source'=>'Upload','upload'=>$u];
	}

    /**
     * Clean up image EXIF data and add watermark
     * @param $image
     */
    public function image_prep($image)
    {
        // Add file title as EXIF ImageDescription tag content in image
        exec($this->bin."exif -o=".$image['tmp_name']." --ifd=0 --tag=ImageDescription --set-value='".$image['name']." © ".date("Y")." UNF Environmental Center' --no-fixup ".$image['tmp_name'],$execresult);

        // Remove GPS EXIF data from image
        exec($this->bin."exif -o=".$image['tmp_name']." --ifd=GPS --tag= --remove ".$image['tmp_name'],$execresult);

        // Add UNF watermark to image
        $w=$this->path."unfwm.gif";$wmfile=$this->path."wm.jpg";
        $cmd="composite -dissolve 75 -gravity southwest ".$w." ".$image['tmp_name']." ".$wmfile;
        exec($this->bin.$cmd,$execresult);
        //echo $cmd;exit;

        return;
    }

    /**
     * Create the THUMB stream file and metadata for an uploaded image
     * @param $image
     * @return array
     */
	public function image_thumb($image)
	{
		clearstatcache(); // Clears previous size of thumb.jpg (actually all files)
		$thumbfile=$this->path.'thumb.jpg';
		$options='-density 300 -resize 340';
		exec($this->bin."convert '".$image['tmp_name']."' ".$options." ".$thumbfile,$execresult);
		$q=['dsLabel'=>'Thumbnail of image','checksumType'=>'DISABLED','logMessage'=>'Added thumbnail stream'];
		$u=['name'=>$image['name'].' thumbnail','type'=>'image/jpeg','tmp_name'=>$thumbfile,'error'=>'0','size'=>filesize($thumbfile)];
		return ['dsID'=>'THUMB','query'=>$q,'source'=>'Upload','upload'=>$u];
	}

    /**
     * Get images for a species
     * @param $id
     * @param string $filter
     * @return mixed
     */
    public function getimages($id,$filter="all")
    {
        $Service = ClassRegistry::init('Service');

        // Find the photos of this fish (use KML stream to show active/inactive so that the Source and THUMB stream images can still be accessed)
        $triples= "?pid <fedora-rels-ext:isPartOf> '".$id."'. ?pid <fedora-view:disseminates> ?dsid. ?pid <dc:creator> ?creator. ?dsid <fedora-model:state> ?state.";
        if ($filter=="all") {
            $filter="FILTER regex(str(?dsid),'KML')"; // Get both active and inactive images
        } elseif ($filter=="Active") {
            $filter="FILTER (regex(str(?dsid),'KML') && regex(str(?state),'#Active'))"; // Get only active images
        } elseif ($filter=="Available") {
            $filter="FILTER (regex(str(?dsid),'KML') && regex(str(?state),'#Active|#Inactive'))"; // Get only active images
        }

        $query="select * where { ".$triples." ".$filter." }";
        $photos=$Service->risearch($query);
        return $photos;
    }
}