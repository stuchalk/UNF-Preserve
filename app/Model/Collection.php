<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the bird table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Collection extends AppModel
{
	public $useTable=['Asset']; // where collections are defined

	// Collections this item is part of
	// $return [list|array]
	public function hascols($pid="",$return="list")
	{
		// Check for valid collection

		// Sparql query to find the parent collection(s) of a collection


		// Get data
		$Service = ClassRegistry::init('Service');
		$data=$Service->risearch($query);
		
		// Format data
		if(!empty($data)&&$return=="list"):	foreach($data as $col) { $cols[$col['pid']]=$col['title']; }
		else:								$cols=$data;
		endif;
		
		// Return
		return $cols;
	}

	// Retrieve all collection objects in the sites namespace
	// $return [list|array]
	public function index($return="list")
	{
		// Sparql query finds all "Active" top level collections - ones that do not have an 'isSubsetOf' relationship
		$query="select * where { ?pid <info:jaffedora/isCollectionOf> ?group. ?pid <fedora-model:state> <fedora-model:Active>. ?pid <dc:title> ?title. FILTER regex(str(?pid),'unf','i') } ORDER BY ?title";
		
		// Get data (array)
		$Service = ClassRegistry::init('Service');
		$data=$Service->risearch($query);
		
		// Format data
		if(!empty($data)&&$return=="list"):	foreach($data as $col) { $cols[$col['pid']]=$col['title']; }
		else:								$cols=$data;
		endif;
		
		// Return data
		return $cols;
	}

	// Find the items of a collection
	// $return [list|array]
	public function items($pid="",$return="list")
	{
		// Check for valid collection
		$this->isValidCol($pid);
		
		// Sparql query to find all the members of a collection
		list($ns,$id)=explode(":",$pid);
		$query=$this->sparqlQuery(array('fields'=>array('isItemOf'),'values'=>array($pid),'places'=>array('end'),'sort'=>'ASC_title'),$ns,'Source');
		
		// Get data (array)
		$Service = ClassRegistry::init('Service');
		$data=$Service->risearch($query);
		
		// Format data
		if(!empty($data)&&$return=="list"):	foreach($data as $item) { $items[$item['pid']]=$item['title']; }
		else:								$items=$data;
		endif;
		
		// Return data
		return $items;
	}

	// Find the stats for a collection (subcollections and items)
	// $return None (returns array)
	public function stats($pid="")
	{
		// Check for valid collection
		$this->isValidCol($pid);
		
		// Get item count
		$data['items']=count($this->items($pid));
		
		// Get subcol count
		$data['subcols']=count($this->subcols($pid));
		
		// Return data
		return $data;
	}

	// Find the subcollections of a collection
	// $return [list|array]
	public function subcols($pid="",$return="list")
	{
		// Check for valid collection
		$this->isValidCol($pid);
		
		// Sparql query to find all objects that are 'subsets' of a collection (i.e. subcollections)
		list($ns,$id)=explode(":",$pid);
		$query=$this->sparqlQuery(array('fields'=>array('isSubsetOf'),'values'=>array($pid),'places'=>array('end'),'sort'=>'ASC_title'),$ns,'Source');
		
		// Get data (array)
		$Service = ClassRegistry::init('Service');
		$data=$Service->risearch($query);
		
		// Format data
		if(!empty($data)&&$return=="list"):	foreach($data as $col) { $subcols[$col['pid']]=$col['title']; }
		else:								$subcols=$data;
		endif;
		
		// Return data
		return $subcols;
	}

}