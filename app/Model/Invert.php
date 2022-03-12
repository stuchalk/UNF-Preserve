<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the invert table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Invert extends AppModel
{
	public $virtualFields = [
		'familyname'=>'CONCAT(`Invert.family`,": ",`Invert.cname`)',
		'fullcname'=>'CONCAT(`Invert.cname`," (",`Invert.sname`,")")',
		'fullsname'=>'CONCAT(`Invert.sname`," (",`Invert.cname`,")")'
	];

	public function stats($field)
	{
		if($field=='all') {
			$data=$this->find('all');
			$return=count($data);
		} else {
			$query='select `'.$field.'`,count(*) as count from inverts group by `'.$field.'` order by `'.$field.'`';
			$data=$this->query($query);
			$return=[];
			foreach($data as $result) { $return[$result['inverts'][$field]]=$result[0]['count']; }
		}
		return $return;
	}
	
	public function stockphoto($url)
	{
		$Website = ClassRegistry::init('Website');
		$allns=$Website->listall();
		list($ns,$invertid)=explode(":",$url);
		$html=file_get_contents($allns[$ns].$invertid);
		if($ns=="bugguide")
		{
			libxml_use_internal_errors(true);
			$html=tidy_parse_string($html);
			$html->cleanRepair();
			$doc= new DOMDocument();
			$doc->loadHTML($html);
			$images=$doc->getElementsByTagName('img');
			// Find the first image of this species and then get the parent page of the image
			foreach($images as $image) {
				$imgurl=$image->attributes->getNamedItem("src")->nodeValue;
				if(stristr($imgurl,"images/cache"))
				{
					$imgurl=$image->parentNode->attributes->getNamedItem("href")->nodeValue;
					break;
				}
			}
			$html2=file_get_contents($imgurl);
			$html2=tidy_parse_string($html2);
			$html2->cleanRepair();
			$doc->loadHTML($html2);
			$imgs=$doc->getElementsByTagName('img');
			// Get the url of the image on this page
			foreach($imgs as $img) {
				//echo $img->attributes->getNamedItem("src")->nodeValue."<br />";
				$rawimgurl=$img->attributes->getNamedItem("src")->nodeValue;
				if(stristr($rawimgurl,"net/images")) { return $rawimgurl; }
			}
			return "NA";
		}
		elseif($ns=="jaxshells")
		{
			// Done by hand (only seven entries)
		}
	}
	
}

?>