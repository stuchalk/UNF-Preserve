<?php
/**
 * jafFedora Controller for Collection functions (via Fedora 3.4.2 - 3.6.2 REST API)
 * URL: https://wiki.duraspace.org/display/FEDORA36/REST+API
 * Used for reading and writing to Fedora-Commons, through models/datasource
 * Version: 1.0 (01/07/13)
 * Copyright 2011-2013 Stuart J. Chalk
 */

class GroupsController extends AppController
{
	public $uses=array('Group','Fobject','Repository','Service','Datastream');

	public function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	// Add a collection
	public function add($output="redirect")
	{
		// Get GET or POST query parameters (needed for Fedora request)
		$query=$this->Collection->getQuery($this);
		
		// Process
		if(!empty($query))
		{
			// Get the PID if needed
			if(!isset($query['ns'])||$query['ns']=="") { $query['ns']=Configure::read('jaf.pidns'); }
			if(!isset($query['pid'])):	list($pid)=$this->Repository->next('1',$query['ns']);
			else:						$pid=$query['pid'];unset($query['pid']);
			endif;
			
			// Add metadata
			$query['meta']['label']=$query['dc']['title'];
			$query['meta']['ownerId']=$query['dc']['creator'];
			
			// Add relationships
			$rels=array();
			$rels[]=array('predicate'=>'isCollection','object'=>'','literal'=>'true');
			if($query['parentcol']!=""):		$rels[]=array('predicate'=>'isSubcollection','object'=>'','literal'=>'true');
												$rels[]=array('predicate'=>'hasCollectionLevel','object'=>'','literal'=>'2');
												$rels[]=array('predicate'=>'isSubsetOf','object'=>$query['parentcol'],'literal'=>'');
			else:								$rels[]=array('predicate'=>'hasCollectionLevel','object'=>'','literal'=>'1');
			endif;
			
			// Create the object
			$data=$this->Fobject->add($pid,$query['meta'],$query['dc'],$query['streams'],$rels);
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',array('output'=>$output));
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/collections/view/'.$pid);
			elseif(isset($this->params['requested'])):	return $data;
			elseif($this->params['isAjax']==1):			$this->layout='ajax';
			endif;
		}
		else
		{
			// Get data about current collections
			$data=$this->Collection->index();
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',array('output'=>$output));
		}
	}

	// Delete a collection
	public function delete($pid="",$output="redirect")
	{
		// Check data
		$this->Fobject->isValidCol($pid,$this->params);

		// Delete a collection from the archive (make the collection object state D) - returns datetime of change
		$data=$this->Fobject->update($pid,array('state'=>'D'));
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid,'output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif($output=="redirect"):				$this->redirect('/collections');
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Get list of collections
	public function index($output="list")
	{
		// Get data
		$data=$this->Group->index($output);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Get the collections this item is part of
	public function cols($pid="",$output="list")
	{
		$data=$this->Group->cols($pid,$output);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}
	
	// Get items in a collection
	public function items($pid="",$output="list")
	{
		// Get collection metadata
		$data=$this->Fobject->profile($pid);
		
		// Get collection metadata
		$data['streams']=$this->Datastream->listall($pid);
		
		// Get items
		$data['items']=$this->Collection->items($pid,$output);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid,'output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Search items in a collection
	public function search($pid="",$terms="",$output="array")
	{
		// Get GET or POST query parameters (needed for Fedora request)
		$query=$this->Collection->getQuery($this);
		
		// Consolidate parameters
		if(!isset($query['pid']))	{ $query['pid']=$pid; }
		if(!isset($query['terms']))	{ $query['terms']=$terms; }
		
		// Check data
		$this->Collection->isValidCol($query['pid'],$this->params);
		if($query['terms']=="")		{ $this->cakeError('jaffedora',array('error'=>'No search term specified','url'=>'Controller:collections->search','params'=>$this->params)); }
		$query['maxResults']="9999";
		
		// Find objects
		$objs=$this->Fobject->fsearch($query,$output);
		
		// Limit to those only related to the collection
		$items=$this->Collection->items($pid);
		$data=array_intersect_key($objs['results'],$items);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid,'terms'=>$terms,'output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Generate stats for a collection
	public function stats($pid="",$output="list")
	{
		// Get collection metadata
		$data=$this->Fobject->profile($pid);
		
		// Get stats
		$data['stats']=$this->Collection->stats($pid);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid,'output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// View a collection
	public function view($pid="",$output="array")
	{
		// Check data
		$this->Collection->isValidCol($pid,$this->params);
		
		// Get data
		$data=$this->Fobject->view($pid);
		
		// Get subcollections
		$data['subcols']=$this->Collection->subcols($pid);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid,'output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Undelete a collection
	public function undelete($pid="",$output="redirect")
	{
		// Check data
		$this->Fobject->isValidCol($pid,$this->params);
		
		// Undelete a collection from the archive (make the collection object state A) - returns datetime of change
		$data=$this->Fobject->update($pid,array('state'=>'A'));
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('pid'=>$pid,'output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif($output=="redirect"):				$this->redirect('/collections/view/'.$pid);
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	// Update a collection
	public function update($pid="",$output="redirect")
	{
		// Check data
		$this->Fobject->isValidCol($pid,$this->params);

		// Get GET or POST query parameters (needed for Fedora request)
		$query=$this->Collection->getQuery($this);
		
		// Get data
		if(!empty($query))
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
			$data['dc']=$this->Datastream->update($pid,'DC',array('file'=>$file,'query'=>array('controlGroup'=>'X')),$output);
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',array('pid'=>$pid,'output'=>$output));
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/Collections/view/'.$pid);
			elseif(isset($this->params['requested'])):	return $data;
			elseif($this->params['isAjax']==1):			$this->layout='ajax';
			endif;
		}
		else
		{
			// Get collection data
			$data=$this->Fobject->view($pid);
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',array('pid'=>$pid,'output'=>$output));
		}
	}

}
?>