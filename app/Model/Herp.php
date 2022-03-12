<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the herp table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Herp extends AppModel
{
	public $virtualFields = [
		'genusname'=>'CONCAT(`Herp.genus`,": ",`Herp.cname`)',
		'fullcname'=>'CONCAT(`Herp.cname`," (",`Herp.sname`,")")',
		'fullsname'=>'CONCAT(`Herp.sname`," (",`Herp.cname`,")")'
	];

	public function stats($field)
	{
		if($field=='all') {
			$data=$this->find('all');
			$return=count($data);
		} else {
			$query='select `'.$field.'`,count(*) as count from herps group by `'.$field.'` order by `'.$field.'`';
			$data=$this->query($query);
			$return=[];
			foreach($data as $result) { $return[$result['herps'][$field]]=$result[0]['count']; }
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
		if($ns=="reptile") {
			libxml_use_internal_errors(true);
			$html=tidy_parse_string($html);
			$html->cleanRepair();
			$doc= new DOMDocument();
			$doc->loadHTML($html);
			if($doc->getElementById("gallery"))
			{
				$gallery=$doc->getElementById("gallery");
				$imgs=$gallery->getElementsByTagName("img");
				foreach($imgs as $img)
				{
					//echo $img->attributes->getNamedItem("src")->nodeValue."<br />";
					$rawimgurl=$img->attributes->getNamedItem("src")->nodeValue;
					if(stristr($rawimgurl,"calphotos")):		return str_replace("128x192","512x768",$rawimgurl);
					elseif(stristr($rawimgurl,"reptarium")):	return str_replace("_t","",$rawimgurl);
					endif;
				}
			}
			return "NA";
		} elseif($ns=="amphi") {
			libxml_use_internal_errors(true);
			$html=tidy_parse_string($html);
			$html->cleanRepair();
			$doc= new DOMDocument();
			$doc->loadHTML($html);
			$imgs=$doc->getElementsByTagName("img");
			foreach($imgs as $img)
			{
				//echo $img->attributes->getNamedItem("src")->nodeValue."<br />";
				$rawimgurl=$img->attributes->getNamedItem("src")->nodeValue;
				if(stristr($rawimgurl,"calphotos")):		return str_replace("128x192","512x768",$rawimgurl);
				elseif(stristr($rawimgurl,"reptarium")):	return str_replace("_t","",$rawimgurl);
				endif;
			}
			return "NA";
		}
	}
}

?>