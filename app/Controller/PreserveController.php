<?php

/**
 * Sawmill Slough Preserve Controller
 * Accesses data sets from MySQL tables and Fedora
 * Version: 1.0 (01/07/13)
 * Copyright 2011-2013 Stuart J. Chalk
 */
class PreserveController extends AppController
{
	public $uses=['Bird','Fish','Herp','Invert','Lichen','Mammal','Plant','Asset','Stream'];
	
	public function beforeFilter()
	{
		$this->Auth->allow();
	}

    /**
     * Get the preserve main page
     */
	public function index()
	{
		$data=[];
		
		// Generate info on available inventories

		// Get stats of inventories
        $invs=['Bird','Fish','Herp','Invert','Lichen','Mammal','Plant'];
        foreach($invs as $inv) {
            $invp=strtolower(Inflector::pluralize($inv));
			$data['invs'][$invp]=$this->$inv->stats('all');
		}

		// Generate info on available collections
		// Collections are sets of objects in Fedora
        $cols=$this->Asset->find('list',['fields'=>['pid','title'],'conditions'=>['type'=>'collection'],'recursive'=>-1]);

        // Get stats of collections
		foreach($cols as $pid=>$title) {
            $members=$this->Asset->find('list',['fields'=>['id','title'],'conditions'=>['collections like'=>'%"'.$pid.'"%'],'recursive'=>-1]);
            $data['cols'][$pid]['title']=$title;
			$data['cols'][$pid]['count']=count($members);
		}
		//debug($data);exit;
		ksort($data['invs']);

		// Set view variables
		$this->set('data',$data);
		
		// Return data to view/requester
		if(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):		$this->layout='ajax';
		endif;
	}
	
    /**
     * Temp function
     */
    public function temp()
	{
		// Find the photos of that have wrong mime type for jpeg
		
		// Source stream
		$triples= "?s <info:jaffedora/isItemOf> <fedora:unfenvc:20> . ?s <fedora-view:lastModifiedDate> ?date .";
		$filter='FILTER (?date < "2014-12-17T00:00:00Z"^^xsd:dateTime)';
		$query="select ?s where { ".$triples." ".$filter." }";
		$source=$this->Service->risearch($query);
		
		foreach($source as $image)
		{
			$pid=$image['s'];
			
			// Download image to phptemp folder (can't use convert directly as it does not handle https)
			$image['tmp_name']='/Volumes/data/phptemp/temp.jpg';
			exec('curl "https://ecenter.unf.edu/datastreams/content/'.$pid.'/Source/download" -o "'.$image['tmp_name'].'"',$execresult);
			
			$stream=$this->Datastream->metadata($pid,"Source");
			(isset($stream['dsAltID'])) ? $image['name']=$stream['dsAltID'] : $image['name']="Source";
			
			$upload=$this->Preserve->image_thumb($image);
			$size=filesize('/Volumes/data/phptemp/thumb.jpg');
			
			$upload['query']['controlGroup']='M';
			$response=$this->Datastream->update($pid,"THUMB",$upload);
			
			if($response['dsSize']==$size):	echo "Success on photo ".$pid."<br />";
			else:							echo "Failure on photo ".$pid."<br />";
			endif;
			
			if(file_exists('/Volumes/data/phptemp/temp.jpg'))		{ unlink('/Volumes/data/phptemp/temp.jpg'); }
			if(file_exists('/Volumes/data/phptemp/thumb.jpg'))		{ unlink('/Volumes/data/phptemp/thumb.jpg'); }
		}
		exit;
	}

    /**
     * gps removal
     */
    public function gps()
    {

        $triples= "?pid <dc:title> ?title. ?pid <fedora-view:disseminates> ?dsid. ?pid <fedora-view:lastModifiedDate> ?date";
        $filter="FILTER (regex(str(?pid),'unfenvc:1[0-9]{3}$') && regex(str(?dsid),'/Original'))"; // Get only preserve objects
        $query="select * where { ".$triples." ".$filter." } order by ASC(?pid)";
        echo $query."<br />";
        $results=$this->Service->risearch($query);
        //echo "<pre>";print_r($results);echo "</pre>";exit;

        foreach($results as $result) {
            // Read file from source stream
            $pid=$result['pid'];$name=$result['title'];
            $data=[];
            $path="/Volumes/data/phptemp/";
            $t=$path."temp.jpg";
            exec('curl "https://ecenter.unf.edu/datastreams/content/'.$pid.'/Source/download" -o "'.$t.'"',$execresult);

            // Add a new stream for the original upload file
            $q=['dsLabel'=>"Source file",'checksumType'=>'DISABLED','logMessage'=>'Added original file'];
            $u=['name'=>$name.' original','type'=>'image/jpeg','tmp_name'=>$t,'error'=>'0','size'=>filesize($t)];
            $s=['query'=>$q,'source'=>'Upload','upload'=>$u];
            $data['original']=$this->Datastream->add($pid,'Original',$s);

            // Extract exif data
            $es=$this->Preserve->image_exif(['tmp_name'=>$t]);

            // Clean EXIF data and add watermark image
            $tm="wm.jpg";
            $wm=$path.$tm;
            $this->Preserve->image_prep(['tmp_name'=>$t],$name,$wm);

            // Create new thumbnail
            $it=$this->Preserve->image_thumb(['tmp_name'=>$wm,'name'=>$name]);

            // Save image back to Source stream
            $sq=['dsLabel'=>'Source file','logMessage'=>'Updated source stream','controlGroup'=>'M'];
            $su=['name'=>$name,'type'=>'image/jpeg','tmp_name'=>$wm,'error'=>'0','size'=>filesize($wm)];
            $ss=['query'=>$sq,'source'=>'Upload','upload'=>$su];
            $data['source']=$this->Datastream->update($pid,'Source',$ss);

            // Save exif data back to EXIF stream
            $es['query']['controlGroup']='X'; //  Must add for update
            $data['exif']=$this->Datastream->update($pid,'EXIF',$es);

            // Save thumbnail back to THUMB stream
            $it['query']['controlGroup']='M'; //  Must add for update
            $data['thumb']=$this->Datastream->update($pid,'THUMB',$it);

            // Cleanup
            if(file_exists('/Volumes/data/phptemp/exif.xml'))	{ unlink('/Volumes/data/phptemp/exif.xml'); }
            if(file_exists('/Volumes/data/phptemp/temp.jpg'))	{ unlink('/Volumes/data/phptemp/temp.jpg'); }
            if(file_exists('/Volumes/data/phptemp/thumb.jpg'))	{ unlink('/Volumes/data/phptemp/thumb.jpg'); }
            if(file_exists('/Volumes/data/phptemp/wm.jpg'))		{ unlink('/Volumes/data/phptemp/wm.jpg'); }

            echo "<pre>";print_r($data);echo "</pre>";
        }
        exit;
    }
}