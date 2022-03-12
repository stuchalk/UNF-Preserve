<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the plant table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Plant extends AppModel
{
	public $virtualFields = [
		'groupname'=>'CONCAT(`Plant.group`,": ",`Plant.cname`)',
		'fullcname'=>'CONCAT(`Plant.cname`," (",`Plant.sname`,")")',
		'fullsname'=>'CONCAT(`Plant.sname`," (",`Plant.cname`,")")'
	];
	
	public function stats($field)
	{
		if($field=='all') {
			$data=$this->find('all');
			$return=count($data);
		} else {
			$query='select `'.$field.'`,count(*) as count from plants group by `'.$field.'` order by `'.$field.'`';
			$data=$this->query($query);
			$return=[];
			foreach($data as $result) { $return[$result['plants'][$field]]=$result[0]['count']; }
		}
		return $return;
	}
	
	public function stockphoto($url)
	{
		$Website = ClassRegistry::init('Website');
		$allns=$Website->listall();
		list($ns,$plantid)=explode(":",$url);
		$html=file_get_contents($allns[$ns].$plantid);
		if($ns=="plantatlas")
		{
			libxml_use_internal_errors(true);
			$html=tidy_parse_string($html);
			$html->cleanRepair();
			$doc= new DOMDocument();
			$doc->loadHTML($html);
			if($doc->getElementById('ctl00_cphBody_liPhotoTool'))
			{
				$browse=$doc->getElementById('ctl00_cphBody_liPhotoTool'); // parent <li> of "Browse Photos"
				$links=$browse->getElementsByTagName("a");
				$imgurl=$links->item(0)->attributes->getNamedItem("href")->nodeValue;
				// Get first image page
				$html2=file_get_contents("http://www.florida.plantatlas.usf.edu/".$imgurl);
				$html2=tidy_parse_string($html2);
				$html2->cleanRepair();
				$doc->loadHTML($html2);
				$imgs=$doc->getElementsByTagName('img');
				// Get the url of the image on this page
				foreach($imgs as $img)
				{
					//echo $img->attributes->getNamedItem("src")->nodeValue."<br />";
					$rawimgurl=$img->attributes->getNamedItem("src")->nodeValue;
					if(stristr($rawimgurl,"plantimage")) { return $rawimgurl; }
				}
			}
			return "NA";
		}
	}
}

?>