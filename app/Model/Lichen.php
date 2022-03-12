<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the lichen table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Lichen extends AppModel
{
	public $virtualFields = [
		'genusname'=>'CONCAT(`Lichen.genus`,": ",`Lichen.cname`)',
		'fullcname'=>'CONCAT(`Lichen.cname`," (",`Lichen.sname`,")")',
		'fullsname'=>'CONCAT(`Lichen.sname`," (",`Lichen.cname`,")")'
	];

	public function stats($field)
	{
		if($field=='all') {
			$data=$this->find('all');
			$return=count($data);
		} else {
			$query='select `'.$field.'`,count(*) as count from lichens group by `'.$field.'` order by `'.$field.'`';
			$data=$this->query($query);
			$return=[];
			foreach($data as $result) { $return[$result['lichens'][$field]]=$result[0]['count']; }
		}
		return $return;
	}

	public function stockphoto($url)
	{
		$Website = ClassRegistry::init('Website');
		$allns=$Website->listall();
		list($ns,$herpid)=explode(":",$url);
		$url=$allns[$ns].$herpid;
		$html=file_get_contents($url);
		if($ns=="lichen")
		{
			// Only a few so done by hand
		}
		elseif($ns=="lichen2")
		{
			// Only a few so done by hand
		}
	}
}

?>