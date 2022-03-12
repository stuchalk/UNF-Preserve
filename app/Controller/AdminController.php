<?php
/**
 * Sawmill Slough Preserve Admin Controller
 * Accesses data sets from MySQL tables and Fedora
 * Version: 2.0 (02/20/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class AdminController extends AppController
{
	public $uses=['Asset','Stream','Preserve','Saxon','Photo'];

    /**
     * Cakephp beforeFilter
     */
    public function beforeFilter()
	{
		$this->Auth->allow();
		$this->Auth->deny('dashboard');
	}
	
	/**
     * Get the preserve main page
     * @return array
     */
	public function index()
	{
		$data=[];
		
		// Generate info on available inventories
		// Data sets are tables in the mysql preserve database
		$invs=$this->Preserve->inventories();

		// Get stats of inventories
		foreach($invs as $inventory)
		{
			$c=ucfirst(Inflector::singularize($inventory));
			$this->loadModel($c);
			$data['invs'][$inventory]=$this->$c->stats('all');
		}
		
		// Generate info on available collections
		// Collections are sets of objects in Fedora
		$query="select * where {?pid <fedora-rels-ext:isPartOf> 'preserve'. ?pid <dc:title> ?title.}";
		$cols=$this->Service->risearch($query);
		
		// Get stats of collections
		foreach($cols as $col)
		{
			$query="select * where { ?pid <info:jaffedora/isItemOf> <fedora:".$col['pid'].">. ?pid <dc:title> ?title.}";
			$data['cols'][$col['pid']]['title']=$col['title'];
			$data['cols'][$col['pid']]['count']=count($this->Service->risearch($query));
		}
		
		// Set view variables
		$this->set('data',$data);
		
		// Return data to view/requester
		if(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):		$this->layout='ajax';
		endif;
	}
	
	/**
     * Admin page for inventories
     */
	public function dashboard()
	{
		$tables=$this->Preserve->inventories();
		$this->set('tables',$tables);
	}

    /**
     * View images for info pages
     * @param string $output
     * @return array
     */
	public function images($output="array")
	{
		$types=['burn','exotic','habitat','special'];
		$allimages=[];
		
		// Get pids of photos
		foreach($types as $type)
		{
			$allimages[$type]=[];
			// Find the photos of this type (use KML stream to show active/inactive so that the Source and THUMB stream images can still be accessed)
			$triples= "?pid <fedora-rels-ext:isPartOf> '".$type."'. ?pid <fedora-view:disseminates> ?dsid. ?pid <dc:creator> ?creator. ?dsid <fedora-model:state> ?state.";
			$filter="FILTER regex(str(?dsid),'KML')"; // Get only active images
			$query="select * where { ".$triples." ".$filter." }";
			$allimages[$type]=$this->Service->risearch($query);
		}
		
		// Get labels of photos for accessing on webpages
		$labels=$this->Photo->find('list',['fields'=>['pid','label']]);
		
		// Set view variables
		$this->set('allimages',$allimages);
		$this->set('labels',$labels);
		$this->set('types',$types);
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($allimages);exit;
		elseif(isset($this->params['requested'])):	return $allimages;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

    /**
     * Upload images for webpages
     * @param string $col
     * @param string $output
     */
	public function addimage($col="",$output="redirect")
	{
		// Get Fedora request query parameters (via GET or POST)
		$query=$this->Fobject->getQuery($this);
		
		// Get data
		if(!empty($query))
		{
			// Get collection information
			$this->Fobject->isValidCol($query['col'],$this->params);
			$pcount=$query['count'];
			
			// Add images
			// Remove empty upload array entries
			for($x=count($query['upload'])-1;$x>-1;$x--)
			{
				if($query['upload'][$x]['name']=="") { unset($query['upload'][$x]); }
			}
			sort($query['upload']);

            $data = [];
            for ($x = 0; $x < count($query['upload']); $x++) {
                $type=$query['upload'][$x]['itype'];
                $data[] = $this->Preserve->addimage($type, ['cname'=>ucfirst($type),'sname'=>ucfirst($type)], $query['upload'][$x], $query['col'], $pcount[$type] + $x + 1);
            }

            // Set view variables
			$this->set('data',$data);
			$this->set('args',['query'=>$query]);
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/admin/images');
			elseif(isset($this->params['requested'])):	return $data;
			elseif($this->params['isAjax']==1):			$this->layout='ajax';
			endif;
		}
		else
		{
			// Get data about current collections
			if($col!=""):	$data=$this->Fobject->view($col);
			else:			$data=$this->Collection->index();
			endif;
		
			// Set view variable
			$this->set('data',$data);
			$this->set('args',['col'=>$col]);
			
			// Return to view
			if($this->params['isAjax']==1) { $this->layout='ajax'; }
		}
	}

    /**
     * Upload new versions of the inventory pdfs
     */
	public function addpdf()
	{
		// Get Fedora request query parameters (via GET or POST)
		$query=$this->Fobject->getQuery($this);

		$titles['birds']="Birds of the UNF Sawmill Slough Preserve";
		$titles['fish']="Fishes of the UNF Sawmill Slough Preserve";
		$titles['inverts']="Insects and Invertebrates of the UNF Sawmill Slough Preserve";
		$titles['lichens']="Lichens of the UNF Sawmill Slough Preserve";
		$titles['mammals']="Mammals of the UNF Campus";
		$titles['plants']="Plants of the UNF Sawmill Slough Preserve";
		$titles['herps']="Reptiles and Amphibians of the UNF Sawmill Slough Preserve";

		// Move uploaded file
		if($query['upload']['name']=="")
		{
			$this->Session->setFlash('Error: No PDF file');
		}
		elseif($query['upload']['size']==0)
		{
			$this->Session->setFlash('Error: Empty PDF file');
		}
		elseif($query['upload']['type']!="application/pdf")
		{
			$this->Session->setFlash('Error: Not a PDF file!');
		}
		else
		{
			$tempfile=WWW_ROOT.'files/pdf/'.$titles[$query['pdftype']].'.pdf';
			if(move_uploaded_file($query['upload']['tmp_name'],$tempfile))
			{
				chmod($tempfile, 0777);
				$this->Session->setFlash('Success: PDF file updated!');
			}
			else
			{
				$this->Session->setFlash('Error: Problem moving PDF');
			}
		}

		// Return to dashboard
		$this->redirect('/admin/dashboard');
	}

    /**
     * Show photo (send to browser)
     * @param $label
     */
	public function photo($label)
	{
		$pid=$this->Photo->find('list',['conditions'=>['label'=>$label],'fields'=>['label','pid']]);
		$this->redirect('/utils/display/'.$pid[$label].'/THUMB/500/download');
	}
	
	/**
	 * Process foxml files to move images into the webroot/files folder for use on new site (via SimpleXML)
     * and add metadata to MySQL (via XSLT/JSON)
     * @param string $section
	 */
	public function foxml($section='xml')
	{
		// move images
		$path=WWW_ROOT.'files/xml/other';
		$xmldir=new Folder($path);
		$datapath='/opt/local/fedora/data/datastreamStore';
		$datadir=new Folder($datapath);
		$files = $xmldir->find('.*\.xml');
		foreach($files as $fidx=>$filename) {
		    if($section=='json') {
                echo "Filename: ".$filename."<br/>";
                $fname=str_replace('.xml','',$filename);
                if($this->Saxon->transform($filename,'foxml.xsl','json')) {
                    $json=file_get_contents(WWW_ROOT.configure::read('jsonfilepath').DS.$fname.'.json');
                } else {
                    echo "JSON file not found/created!";exit;
                }
                $asset=json_decode($json,true);

                // get asset metadata and save in DB
                $meta['pid']=$asset['pid'];$pid=$asset['pid'];
                // check if this asset has already been added
                $found=$this->Asset->find('first',['conditions'=>['pid'=>$meta['pid']]]);
                if(!$found) {
                    // FoxML property section
                    $props=$asset['props'];
                    foreach($props as $label=>$prop) {
                        list($ns,$field)=explode("#",$label);
                        if(preg_match('/^(.+?) (Photo \d+)$/',$prop,$m)) {
                            $meta['title']=$m[1];
                            $meta['photo']=$m[2];
                        } else {
                            $fields=['state'=>'state','label'=>'title','ownerId'=>'owner','createdDate'=>'createdDate','lastModifiedDate'=>'lastModifiedDate'];
                            if(in_array($field,array_keys($fields))) {
                                if($field=='createdDate'||$field=='lastModifiedDate') {
                                    $meta[$fields[$field]]=date("Y-m-d H:i:s",strtotime($prop));
                                } else {
                                    $meta[$fields[$field]]=$prop;
                                }
                            }
                        }
                    }
                    //debug($meta);exit;
                    $dcs=$asset['dcs'];$ids=[];
                    // DC section - extract data from the most recent stream version
                    foreach($dcs as $idx=>$dc) { $ids[$idx]=(int)str_replace('DC.','',$dc['attrs']['id']); }
                    $dcidx=array_search(max($ids),$ids);
                    $content=$dcs[$dcidx]['content'];
                    $meta['creator']=$content['creator'];
                    if(isset($content['description'])) { $meta['description']=$content['description']; }
                    $meta['format']=$content['format'];
                    $meta['project']='unfenvc';
                    $meta['type']=$content['type'];
                    // RELS section find out if this asset is a collection or is part of a collection
                    $rels=$asset['rels'];$ids=[];
                    foreach($rels as $idx=>$rel) { $ids[$idx]=(int)str_replace('RELS-EXT.','',$rel['attrs']['id']); }
                    $relidx=array_search(max($ids),$ids);
                    $content=$rels[$relidx]['content'];
                    if(isset($content['isCollection'])) {
                        // is a collection
                        $meta['scope']='collection';
                    } else {
                        $meta['scope']='file';
                    }
                    if(isset($content['isitemof'])) {
                        // part of a collection
                        $meta['collections']='{ "col": "'.str_replace('info:fedora/','',$content['isitemof']).'" }';
                    } elseif(isset($content['ismemberof'])) {
                        $meta['collections']='{ "col": "'.str_replace('info:fedora/','',$content['ismemberof']).'" }';
                    }
                    if(isset($content['ispartof'])) {
                        $meta['resource']=$content['ispartof'];
                    }
                    $this->Asset->create();
                    $saved=$this->Asset->save(['Asset'=>$meta]);
                    if($saved) {
                        $aid=$this->Asset->id;
                    } else {
                        echo "Asset not saved<br/>";
                        debug($meta);exit;
                    }
                } else {
                    echo "Asset already added<br/>";
                    $aid=$found['Asset']['id'];
                }

                // add the streams
                $srcs=$asset['sources'];$ext="";
                foreach($srcs as $idx=>$src) {
                    $found=$this->Stream->find('first',['conditions'=>['asset_id'=>$aid,'streamid'=>$src['attrs']['id']]]);
                    if(!$found) {
                        $meta=$src['attrs'];
                        $meta['md5']=$src['content']['md5'];
                        if($meta['mimetype']=="image/jpeg"||$meta['mimetype']=="image/jpg"||$meta['mimetype']=="image/pjpeg") {
                            $ext="jpg";
                        } elseif($meta['mimetype']=="image/png") {
                            $ext="png";
                        } elseif($meta['mimetype']=="text/xml"||$meta['mimetype']=="application/vnd.google-earth.kml+xml") {
                            $ext="xml";
                        } else {
                            echo "Add extension for mime type: ".$meta['mimetype'];exit;
                        }
                        $meta['path']='files'.DS.'img'.DS.str_replace(':','',$pid.DS.$meta['id']).'.'.$ext;
                        $meta['asset_id']=$aid;
                        $meta['streamid']=$meta['id'];unset($meta['id']); // crosswalk
                        if(isset($meta['format_uri'])) {
                            $meta['formaturi'] = $meta['format_uri'];unset($meta['format_uri']); // crosswalk
                        }
                        if(isset($meta['alt_ids'])) {
                            $meta['altids']=$meta['alt_ids']; unset($meta['alt_ids']); // crosswalk
                        }
                        $meta['pid']=$pid;
                        if($meta['md5']=='') { unset($meta['md5']); }
                        $this->Stream->create();
                        $saved=$this->Stream->save(['Stream'=>$meta]);
                        if($saved) {
                            echo "Stream '".$meta['streamid']."' saved<br/>";
                        } else {
                            echo "Stream '".$meta['streamid']."' not saved!<br/>";
                        }
                    } else {
                        echo "Stream ".$found['Stream']['streamid']." already added<br/>";
                    }
                }

                $exifs=$asset['exifs'];$ext="";
                foreach($exifs as $idx=>$exif) {
                    $found=$this->Stream->find('first',['conditions'=>['asset_id'=>$aid,'streamid'=>$exif['attrs']['id']]]);
                    if(!$found) {
                        $meta=$exif['attrs'];
                        $meta['content']=json_encode($exif['content']);
                        if($meta['mimetype']=="image/jpeg"||$meta['mimetype']=="image/jpg"||$meta['mimetype']=="image/pjpeg") {
                            $ext="jpg";
                        } elseif($meta['mimetype']=="image/png") {
                            $ext="png";
                        } elseif($meta['mimetype']=="text/xml"||$meta['mimetype']=="application/vnd.google-earth.kml+xml") {
                            $ext="xml";
                        } else {
                            echo "Add extension for mime type: ".$meta['mimetype'];exit;
                        }
                        if($ext=='jpg') {
                            $meta['path']='files'.DS.'img'.DS.str_replace(':','',$pid.DS.$meta['id']).'.'.$ext;
                        }
                        $meta['asset_id']=$aid;
                        $meta['streamid']=$meta['id'];unset($meta['id']); // crosswalk
                        if(isset($meta['format_uri'])) {
                            $meta['formaturi'] = $meta['format_uri'];unset($meta['format_uri']); // crosswalk
                        }
                        if(isset($meta['alt_ids'])) {
                            $meta['altids']=$meta['alt_ids']; unset($meta['alt_ids']); // crosswalk
                        }
                        $meta['pid']=$pid;
                        if(isset($meta['md5'])&&$meta['md5']=='') { unset($meta['md5']); }
                        $this->Stream->create();
                        $saved=$this->Stream->save(['Stream'=>$meta]);
                        if($saved) {
                            echo "Stream '".$meta['streamid']."' saved<br/>";
                        } else {
                            echo "Stream '".$meta['streamid']."' not saved!<br/>";
                        }
                    } else {
                        echo "Stream ".$found['Stream']['streamid']." already added<br/>";
                    }

                }

                $kmls=$asset['kmls'];$ext="";
                foreach($kmls as $idx=>$kml) {
                    $found=$this->Stream->find('first',['conditions'=>['asset_id'=>$aid,'streamid'=>$kml['attrs']['id']]]);
                    if(!$found) {
                        $meta=$kml['attrs'];
                        $meta['content']=json_encode($kml['content']);
                        if($meta['mimetype']=="image/jpeg"||$meta['mimetype']=="image/jpg"||$meta['mimetype']=="image/pjpeg") {
                            $ext="jpg";
                        } elseif($meta['mimetype']=="image/png") {
                            $ext="png";
                        } elseif($meta['mimetype']=="text/xml"||$meta['mimetype']=="application/vnd.google-earth.kml+xml") {
                            $ext="xml";
                        } else {
                            echo "Add extension for mime type: ".$meta['mimetype'];exit;
                        }
                        if($ext=='jpg') {
                            $meta['path']='files'.DS.'img'.DS.str_replace(':','',$pid.DS.$meta['id']).'.'.$ext;
                        }
                        $meta['asset_id']=$aid;
                        $meta['streamid']=$meta['id'];unset($meta['id']); // crosswalk
                        if(isset($meta['format_uri'])) {
                            $meta['formaturi'] = $meta['format_uri'];unset($meta['format_uri']); // crosswalk
                        }
                        if(isset($meta['alt_ids'])) {
                            $meta['altids']=$meta['alt_ids']; unset($meta['alt_ids']); // crosswalk
                        }
                        $meta['pid']=$pid;
                        if(isset($meta['md5'])&&$meta['md5']=='') { unset($meta['md5']); }
                        $this->Stream->create();
                        $saved=$this->Stream->save(['Stream'=>$meta]);
                        if($saved) {
                            echo "Stream '".$meta['streamid']."' saved<br/>";
                        } else {
                            echo "Stream '".$meta['streamid']."' not saved!<br/>";
                        }
                    } else {
                        echo "Stream ".$found['Stream']['streamid']." already added<br/>";
                    }

                }

                $thumbs=$asset['thumbs'];$ext="";
                foreach($thumbs as $idx=>$thumb) {
                    $found=$this->Stream->find('first',['conditions'=>['asset_id'=>$aid,'streamid'=>$thumb['attrs']['id']]]);
                    if(!$found) {
                        $meta=$thumb['attrs'];
                        if($meta['mimetype']=="image/jpeg"||$meta['mimetype']=="image/jpg"||$meta['mimetype']=="image/pjpeg") {
                            $ext="jpg";
                        } elseif($meta['mimetype']=="image/png") {
                            $ext="png";
                        } elseif($meta['mimetype']=="text/xml"||$meta['mimetype']=="application/vnd.google-earth.kml+xml") {
                            $ext="xml";
                        } else {
                            echo "Add extension for mime type: ".$meta['mimetype'];exit;
                        }
                        if($ext=='jpg'||$ext=='png') {
                            $meta['path']='files'.DS.'img'.DS.str_replace(':','',$pid.DS.$meta['id']).'.'.$ext;
                        }
                        $meta['asset_id']=$aid;
                        $meta['streamid']=$meta['id'];unset($meta['id']); // crosswalk
                        if(isset($meta['format_uri'])) {
                            $meta['formaturi'] = $meta['format_uri'];unset($meta['format_uri']); // crosswalk
                        }
                        if(isset($meta['alt_ids'])) {
                            $meta['altids']=$meta['alt_ids']; unset($meta['alt_ids']); // crosswalk
                        }
                        $meta['pid']=$pid;
                        if(isset($meta['md5'])&&$meta['md5']=='') { unset($meta['md5']); }
                        $this->Stream->create();
                        $saved=$this->Stream->save(['Stream'=>$meta]);
                        if($saved) {
                            echo "Stream '".$meta['streamid']."' saved<br/>";
                        } else {
                            echo "Stream '".$meta['streamid']."' not saved!<br/>";
                        }
                    } else {
                        echo "Stream ".$found['Stream']['streamid']." already added<br/>";
                    }

                }

            }

			// The images/thumbnails were moved to the img folder using the code below
			// the XML was processed using simplexml not the XSLT
            if($section=='xml') {
                echo "Filename: ".$filename."<br/>";
                $fname=str_replace('.xml','',$filename);
                $xml=simplexml_load_file(WWW_ROOT.'files'.DS.'xml'.DS.'other'.DS.$filename);
                $xml->registerXPathNamespace('f','info:fedora/fedora-system:def/foxml#');
                // get source files and move to img folder
                $temp=$xml->xpath("//f:datastream[@ID='Source']/f:datastreamVersion");
                $srcs=json_decode(json_encode($temp),true);
                foreach($srcs as $src) {
                    $id=$src['@attributes']['ID'];
                    $size=$src['@attributes']['SIZE'];
                    $type=$src['@attributes']['MIMETYPE'];
                    preg_match('/^(.+?)(\d+)$/',$fname,$m);
                    $srcpath='info:fedora/'.$m[1].':'.$m[2].'/Source/'.$id;
                    $esrcpath=str_replace('.','\.',urlencode($srcpath));
                    $found=$datadir->findRecursive($esrcpath);
                    if(!empty($found)) {
                        // copy file to WWW_ROOT/files/img/<id>/<filename>
                        $dirpath=WWW_ROOT.'files/img/'.$fname;
                        $dir = new Folder($dirpath, true, 0777);
                        if($type=='image/jpg'||$type='image/jpeg') {
                            $ext='jpg';
                        } else {
                            $ext='?';
                        }
                        $copypath=$dirpath.'/'.$id.'.'.$ext;
                        if(file_exists($copypath)) {
                            echo "File '".$copypath."' already copied<br/>";
                        } else {
                            copy($found[0],$copypath);
                            // check copy and filesize
                            if(file_exists($copypath)) {
                                $fsize=filesize($copypath);
                                if($fsize==$size) {
                                    echo "File ".$copypath." verified<br/>";
                                } elseif($size==-1) {
                                    echo "File size is -1 in FoxML ".$copypath."<br/>";
                                } else {
                                    echo "Inconsistent file size of ".$copypath."<br/>";
                                }
                            } else {
                                echo "Copy failed on ".$copypath."<br/>";
                            }
                        }
                    } else {
                        echo "File ".$srcpath." not found<br />";
                    }
                }

                // get thumbnail files and move to img folder
                $temp=$xml->xpath("//f:datastream[@ID='THUMB']/f:datastreamVersion");
                $tmbs=json_decode(json_encode($temp),true);
                foreach($tmbs as $tmb) {
                    $id=$tmb['@attributes']['ID'];
                    $size=$tmb['@attributes']['SIZE'];
                    $type=$tmb['@attributes']['MIMETYPE'];
                    preg_match('/^(.+?)(\d+)$/',$fname,$m);
                    $srcpath='info:fedora/'.$m[1].':'.$m[2].'/THUMB/'.$id;
                    $esrcpath=str_replace('.','\.',urlencode($srcpath));
                    $found=$datadir->findRecursive($esrcpath);
                    if(!empty($found)) {
                        // copy file to WWW_ROOT/files/img/<id>/<filename>
                        $dirpath=WWW_ROOT.'files/img/'.$fname;
                        $dir = new Folder($dirpath, true, 0777);
                        if($type=='image/jpg'||$type='image/jpeg') {
                            $ext='jpg';
                        } else {
                            $ext='?';
                        }
                        $copypath=$dirpath.'/'.$id.'.'.$ext;
                        if(file_exists($copypath)) {
                            echo "File '".$copypath."' already copied<br/>";
                        } else {
                            copy($found[0],$copypath);
                            // check copy and filesize
                            if(file_exists($copypath)) {
                                $fsize=filesize($copypath);
                                if($fsize==$size) {
                                    echo "File ".$copypath." verified<br/>";
                                } elseif($size==-1) {
                                    echo "File size is -1 in FoxML ".$copypath."<br/>";
                                } else {
                                    echo "Inconsistent file size of ".$copypath."<br/>";
                                }
                            } else {
                                echo "Copy failed on ".$copypath."<br/>";
                            }
                        }
                    } else {
                        echo "File ".$srcpath." not found<br />";
                    }
                }
            }
		}
		exit;
	}
}
