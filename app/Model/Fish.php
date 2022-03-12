<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the fish table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Fish extends AppModel
{
	public $virtualFields = [
		'groupname'=>'CONCAT(`Fish.species`,": ",`Fish.cname`)',
		'fullcname'=>'CONCAT(`Fish.cname`," (",`Fish.sname`,")")',
		'fullsname'=>'CONCAT(`Fish.sname`," (",`Fish.cname`,")")'
	];
	
	public function stats($field)
	{
		if($field=='all') {
			$data=$this->find('all');
			$return=count($data);
		} else {
			$query='select `'.$field.'`,count(*) as count from fish group by `'.$field.'` order by `'.$field.'`';
			$data=$this->query($query);
			$return=[];
			foreach($data as $result) { $return[$result['fish'][$field]]=$result[0]['count']; }
		}
		return $return;
	}
	
	public function stockphoto($url)
	{
		$Website = ClassRegistry::init('Website');
		$allns=$Website->listall();
		list($ns,$fishid)=explode(":",$url);
		$html=file_get_contents($allns[$ns].$fishid);
		if($ns=="fish") {
			// Obtained by hand as there were only 15
		}
	}
}

?>