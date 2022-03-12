<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the mammal table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Mammal extends AppModel
{
	public $virtualFields = [
		'groupname'=>'CONCAT(`Mammal.species`,": ",`Mammal.cname`)',
		'fullcname'=>'CONCAT(`Mammal.cname`," (",`Mammal.sname`,")")',
		'fullsname'=>'CONCAT(`Mammal.sname`," (",`Mammal.cname`,")")'
	];
	
	public function stats($field)
	{
		if($field=='all') {
			$data=$this->find('all');
			$return=count($data);
		} else {
			$query='select `'.$field.'`,count(*) as count from mammals group by `'.$field.'` order by `'.$field.'`';
			$data=$this->query($query);
			$return=[];
			foreach($data as $result) { $return[$result['mammals'][$field]]=$result[0]['count']; }
		}
		return $return;
	}
	
	public function stockphoto($url)
	{
		$Website = ClassRegistry::init('Website');
		$allns=$Website->listall();
		list($ns,$fishid)=explode(":",$url);
		$html=file_get_contents($allns[$ns].$fishid);
		if($ns=="mammal")
		{
			// Nothing to do
		}
	}
}