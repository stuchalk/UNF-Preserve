<?php
/**
 * jafFedora Controller for Item functions (via Fedora 3.4.2 - 3.6.2 REST API)
 * URL: https://wiki.duraspace.org/display/FEDORA36/REST+API
 * Used for reading and writing to Fedora-Commons, through models/datasource
 * Version: 1.0 (01/07/13)
 * Copyright 2011-2013 Stuart J. Chalk
 */

class ItemsController extends AppController
{
	public $uses=array('Fobject','Item','Repository','Datastream','Collection','Service');

	public function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	// Add an item
	public function add($col="",$output="redirect")
	{
		// Get Fedora request query parameters (via GET or POST)
		$query=$this->Fobject->getQuery($this);
		
		// Get data
		if(!empty($query))
		{
			// Initalize variables
			$pid="";$meta=$dc=$streams=$rels=array();
			//echo "Query<br /><pre>";print_r($query);echo "</pre>";
			
			// Generate the pid
			if(!empty($query['pid'])):	$pid=$query['pid'];unset($query['pid']);
			else:						list($pid)=$this->Repository->next('1',Configure::read('jaf.pidns'));
			endif;
			
			// Define the object metadata (remaining values are defaults or hardcoded)
			$meta=array('label'=>$query['dc']['title'],'ownerId'=>$query['ownerId']);
			//echo "Meta<br /><pre>";print_r($meta);echo "</pre>";
			
			// DC - title, description, creator, rights, type
			$dc=$query['dc'];
			if(!isset($dc['creator'])) { $dc['creator']=array(); }
			// Creator
			if($dc['creatorText']!="") { $dc['creator']=array_merge($dc['creator'],explode(";",$dc['creatorText'])); }
			unset($dc['creatorText']);
			// Format (filetype)
			if($query['upload']['size']>0) { $dc['format']=$query['upload']['type']; }
			// Type (dc types)
			if($dc['type']=="Website") { $dc['format']='text/html'; }
			// Language
			$dc['language']='en-US';
			//echo "DC<br /><pre>";print_r($dc);echo "</pre>";
			
			// Move uploaded file
			if(isset($query['upload']))
			{
				$tempfile='/Volumes/data/phptemp/'.$query['upload']['name'];
				move_uploaded_file($query['upload']['tmp_name'],$tempfile);chmod($tempfile, 0777);
				$query['upload']['tmp_name']=$tempfile;
			}
			
			// Streams
			
			// EXIF - from image file
			if(isset($query['upload']))
			{
				$exiffile='/Volumes/data/phptemp/exif.xml';
				exec("exif -x '".$query['upload']['tmp_name']."' > ".$exiffile,$execresult);
				$q=array('dsLabel'=>"EXIF Data",'checksumType'=>'DISABLED','logMessage'=>'Added EXIF file');
				$u=array('name'=>'exif.xml','type'=>'text/xml','tmp_name'=>$exiffile,'error'=>'0','size'=>filesize($exiffile));
				$streams[]=array('dsID'=>'EXIF','query'=>$q,'source'=>'Upload','upload'=>$u);
			}
			
			// KML - this images chuck from parent collection KML file
			$colstrs=$this->Datastream->listall($query['col']);
			if(isset($colstrs['KML']))
			{
				$kmlfile='/Volumes/data/phptemp/marker.kml';
				$temp=$this->Datastream->content($query['col'],'KML',array(),'raw');
				$colkml=simplexml_load_string($temp);
				$colkml->registerXPathNamespace('k','http://www.opengis.net/kml/2.2');
				$kml=$colkml->xpath('//*[text()="'.$query['upload']['name'].'"]/parent::*'); // Gets the parent node of the filename as <name>
				$file="<?xml version=\"1.0\" encoding=\"UTF-8\"?><kml xmlns=\"http://www.opengis.net/kml/2.2\">";
				$file.=$kml[0]->asXML()."</kml>";
				$handle=fopen($kmlfile,'w');
				fwrite($handle,$file);
				fclose($handle);
				$q=array('dsLabel'=>"OGC/Google KML(GPS) Data",'checksumType'=>'DISABLED','logMessage'=>'Added KML file');
				$u=array('name'=>'marker.kml','type'=>'application/vnd.google-earth.kml+xml','tmp_name'=>$kmlfile,'error'=>'0','size'=>filesize($kmlfile));
				$streams[]=array('dsID'=>'KML','query'=>$q,'source'=>'Upload','upload'=>$u);
			}
			
			// RELS-EXT - relationships to the collection and any sub-collection
			$rels=array();
			if($query['col']!="") { $rels[]=array('predicate'=>'isItemOf','object'=>$query['col'],'literal'=>''); }
			if(isset($query['subcols'])&&!empty($query['subcols']))
			{
				foreach($query['subcols'] as $subcol) { $rels[]=array('predicate'=>'isItemOf','object'=>$subcol,'literal'=>''); }
			}
			
			// Source - image file
			if($query['upload']['size']>0)
			{
				$q=array('dsLabel'=>"Source file",'checksumType'=>'DISABLED','logMessage'=>'Added source file');
				$streams[]=array('dsID'=>'Source','query'=>$q,'source'=>'Upload','upload'=>$query['upload']);
			}
			//echo "Streams<br /><pre>";print_r($streams);echo "</pre>";
			//echo "Rels<br /><pre>";print_r($rels);echo "</pre>";exit;
			
			// Create item
			$data=$this->Fobject->add($pid,$meta,$dc,$streams,$rels);
			
			// Delete temp files
			if(file_exists($tempfile))		{ unlink($tempfile); }
			if(file_exists($exiffile))		{ unlink($exiffile); }
			if(file_exists($kmlfile))		{ unlink($kmlfile); }
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',array('query'=>$query));
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/items/view/'.$pid);
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
			$this->set('args',array('col'=>$col));
			
			// Return to view
			if($this->params['isAjax']==1) { $this->layout='ajax'; }
		}
	}

	// Delete an item
	public function delete($pid="",$output="redirect")
	{
		// Check data
		$this->Fobject->isValidItem($pid,$this->params);
		
		// Delete an item from the archive (make the item object state D so it can be reinstated)
		$data=$this->Fobject->update($pid,array('state'=>'D'),$output);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid,'output'=>$output));

		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif($output=="redirect"):				$this->redirect('/');
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Search for items using findObjects
	public function fsearch($terms="",$maxResults="9999",$output="list")
	{
		// Get Fedora request query parameters (via GET or POST)
		$query=$this->Fobject->getQuery($this);
		
		// Check parameters
		if(!isset($query['terms']))			{ $query['terms']=$terms; }
		if(!isset($query['maxResults']))	{ $query['maxResults']=$maxResults; }
		if($query['terms']=="")	{ $this->cakeError('jaffedora',array('error'=>'No search term specified','url'=>'Controller:objects->fsearch','params'=>$this->params)); }

		// Find objects
		$data=$this->Fobject->fsearch($query,$output);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('terms'=>$terms,'maxResults'=>$maxResults,'output'=>$output));

		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Search for items using gsearch fulltext
	public function gsearch($term="",$output="array")
	{
		// Get Fedora request query parameters (via GET or POST)
		$query=$this->Fobject->getQuery($this);

		// Consolidate parameters
		if(!isset($query['term'])) { $query['term']=$term; }

		// Check data
		if($query['term']=="") { $this->cakeError('objects',array('error'=>'No search term supplied!','url'=>'Controller:objects->gsearch')); }
		
		// Get data (result list is filtered for this namespace in the search function)
		$data=$this->Service->gsearch('any',$query['term']);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('term'=>$term,'output'=>$output));

		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Get the collections this item is part of
	public function hascols($pid="",$output="list")
	{
		$data=$this->Item->hascols($pid,$output);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Get list of all content items
	public function index($output="list")
	{
		// Define return format from model
		$return=$this->Fobject->getReturn($output);
		
		// Get data
		$data=$this->Item->index($return);
		
		// Organize by first letter
		$alpha=array();
		foreach($data as $pid=>$title)
		{
			$clean=str_replace("<i>","",$title);
			if(!isset($alpha[$clean[0]])) $alpha[$clean[0]]=array();
			$alpha[$clean[0]][$pid]=$title;
		}
		
		// Set view variables
		$this->set('data',$alpha);
		$this->set('args',array('output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// List all items (or items in a collection)
	public function listall($col="",$output="list")
	{
		// All items or only those in a collection
		if($col==""):	$data=$this->Item->index($output); // Reuse the item index method
		else:			$this->Item->isValidCol($pid,$this->params);
						$data=$this->Collection->items($col,$output); // Reuse from collection model
		endif;
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('col'=>$col,'output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Search for items (or get a count of current unique values if $value is *) using risearch
	public function rsearch($field="",$value="*",$place="any",$sort="ASC_T",$limit=0,$offset=0,$output="array")
	{
		// Define return format from model
		$return=$this->Service->getReturn($output);
		
		// Get query and  parameters (needed for all Fedora requests)
		$params=$this->Fobject->getQuery($this);
		
		// Consolidate fields
		if(!isset($params['field']))	{ $params['field']=$field; }
		if(!isset($params['value']))	{ $params['value']=$value; }
		if(!isset($params['place']))	{ $params['place']=$place; }
		if(!isset($params['sort']))		{ $params['sort']=$sort; }
		if(!isset($params['limit']))	{ $params['limit']=$limit; }
		if(!isset($params['offset']))	{ $params['offset']=$offset; }
		if(!isset($params['query']))	{ $params['query']=""; }
		if(isset($params['output']))	{ $output=$params['output'];unset($params['output']); }
		
		// Send error if nothing to search on
		if($params['field']==""&&$params['query']=="")
		{
			$this->cakeError('jaffedora',array('error'=>'No search field','url'=>'Controller:items->rsearch','params'=>$this->params));
		}
		
		// Get/make query string
		if($params['query']!=""):		$query=$params['query'];
		else:							unset($params['query']);$query=$this->Fobject->sparqlQuery($params);
		endif;
		
		// Get data (result list is filtered for this namespace in the risearch function)
		$data=$this->Service->risearch($query,'tuples',array(),$return);
		
		// Generate stats
		if($output=="count")
		{
			$results=$data;unset($data);
			foreach($results as $result)
			{
				$term=$result[$field];
				if(isset($data[$term])):	$data[$term]++;
				else:						$data[$term]=1;
				endif;
			}
		}
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',$params+array('output'=>$output)+array('query'=>$query));

		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// View an item
	public function view($pid="",$output="array")
	{
		// Check that this is an item
		$this->Item->isValidItem($pid,$this->params);
		
		// Get item view
		$data=$this->Fobject->view($pid);
		
		// Find collections
		$data['cols']=$this->Item->hasCols($pid);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid,'output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Update an item
	public function update($pid="",$output="redirect")
	{
		// Check data
		$this->Fobject->isValidItem($pid,$this->params);
		
		// Get GET or POST query parameters (needed for Fedora request)
		$query=$this->Item->getQuery($this);
		
		// Get data
		if(isset($query))
		{
			// Do object label check if it needs to be updated
			$data=$this->Fobject->view($pid);
			if($query['dc']['title']!=$data['objLabel']) { $data['label']=$this->Fobject->update($pid,array('label'=>$query['dc']['title']),'array'); }
			
			// Update the DC datastream (SimpleXML does not handle the DC stream because of namespaces)
			$dc=$this->Datastream->content($pid,'DC',array(),'raw');
			$xml=simplexml_load_string($dc);
			$dc=$query['dc'];
			$dcns=$xml->children('http://purl.org/dc/elements/1.1/');
			if($dc['title']!= (string) $dcns->title)				{ $dcns->title=$dc['title']; }
			if($dc['creator']!= (string) $dcns->creator)			{ $dcns->creator=$dc['creator']; }
			if($dc['subject']!= (string) $dcns->subject)			{ $dcns->subject=$dc['subject']; }
			if($dc['description']!= (string) $dcns->description)	{ $dcns->description=$dc['description']; }
			
			// Update the datastream
			$file=array('source'=>'text','text'=>$xml->asXML());
			$data['dc']=$this->Datastream->update($col,'DC',array('file'=>$file,'query'=>array('controlGroup'=>'X')),$output);
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',array('pid'=>$pid,'output'=>$output));
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/items/view/'.$pid);
			elseif(isset($this->params['requested'])):	return $data;
			elseif($this->params['isAjax']==1):			$this->layout='ajax';
			endif;
		}
		else
		{
			// Get collection data
			$data=$this->Fobject->view($col);
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',array('pid'=>$pid,'output'=>$output));
		}
	}

	// Undelete an item
	public function undelete($pid="",$output="redirect")
	{
		// Check data
		$this->Fobject->isValidItem($pid,$this->params);
		
		// Undelete an item from the archive (make the item object state A again)
		$data=$this->Fobject->update($pid,array('state'=>'A'),$output);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid,'output'=>$output));

		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif($output=="redirect"):				$this->redirect('/items/view/'.$pid);
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

}
?>