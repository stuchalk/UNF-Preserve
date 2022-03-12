<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the bird table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Bird extends AppModel
{
	public $virtualFields = [
		'groupname'=>'CONCAT(`Bird.group`,": ",`Bird.cname`)',
		'fullcname'=>'CONCAT(`Bird.cname`," (",`Bird.sname`,")")',
		'fullsname'=>'CONCAT(`Bird.sname`," (",`Bird.cname`,")")'
	];

	public function stats($field)
	{
		if($field=='all') {
			$data=$this->find('all');
			$return=count($data);
		} else {
			$query='select `'.$field.'`,count(*) as count from birds group by `'.$field.'` order by `'.$field.'`';
			$data=$this->query($query);
			$return=[];
			foreach($data as $result) { $return[$result['birds'][$field]]=$result[0]['count']; }
		}
		return $return;
	}

	public function stockphoto($url)
	{
		$Website = ClassRegistry::init('Website');
		$allns=$Website->listall();
		list($ns,$birdid)=explode(":",$url);
		$html=file_get_contents($allns[$ns].$birdid);
		if($ns=="birds") {
			libxml_use_internal_errors(true);
			$html=tidy_parse_string($html);
			$html->cleanRepair();
			$doc= new DOMDocument();
			$doc->loadHTML($html);
			$imgs=$doc->getElementsByTagName("img");
			foreach($imgs as $img)
			{
				$rawimgurl=$img->attributes->getNamedItem("src")->nodeValue;
				if(stristr($rawimgurl,"PHOTO/LARGE")) { return "http://www.allaboutbirds.org/".$rawimgurl; }
			}
			return "NA";
		}
	}
}

?>