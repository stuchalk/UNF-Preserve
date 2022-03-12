<?php
/**
 * jafFedora Controller for Utility functions
 * URL: https://wiki.duraspace.org/display/FEDORA36/REST+API
 * Used for reading and writing to Fedora-Commons, through models/datasource
 * Version: 1.0 (01/07/13)
 * Copyright 2011-2013 Stuart J. Chalk
 */

class UtilsController extends AppController
{
	public $uses=['Util','Stream','Service','Item'];

	public function beforeFilter()
	{
		$this->Auth->allow();
	}

	// Display an element via ajax
	public function ajax($element)
	{
		$params=$this->params['form'];
		
		// Form data submitted must have $params['prestr] and $params['count']
		$this->set('element',$element);
		$this->set('params',array('prepend'=>$params['prestr'].$params['count'].'.')+$params);
		$this->layout='ajax';
	}

	// Displays an object datastream in a web page (uses ajax layout)
	public function display($pid="",$dsid="",$width="500",$output="page")
	{
		// Get datastream content
		$data=$this->Datastream->content($pid,$dsid);
		
		// Download
		if($output=="download")
		{
			// Internal from fedora
			header("Content-Type: ".$data['headers']['Content-Type']);
			header("Content-Disposition: ".$data['headers']['content-disposition']);
			readfile($data['url']);
			exit;
		}
		
		// Set view variables
		$this->set('url',$data['exturl']);
		$this->set('args',array('pid'=>$pid,'dsid'=>$dsid,'width'=>$width));
		
		// Set layout
		$this->layout='ajax';
	}
	
	// Displays Google Books embedded view in webpage
	public function gbook($pid="")
	{
		// Get datastream content
		$data=$this->Datastream->content($pid,'DC');
		
		// Set view variables
		$this->set('data',$data['content']['Dc']);  // Google books URL is saved in relation field
		
		// Set layout
		$this->layout='ajax';
	}
	
	// Displays an objects PDF/DOCX/XSLX/PPTX file datastream in a web page (uses ajax layout)
	public function gviewer($pid="",$dsid="")
	{
		// Get content URL - $obj['exturl]
		$obj=$this->Datastream->content($pid,$dsid);
		
		// Set view variables
		$this->set('url',$obj['exturl']);
		
		// Set layout
		$this->layout='ajax';
	}

	public function test()
	{
		// KML - this images chuck from parent collection KML file
		$col='unfemap:2';
		$query=array('upload'=>array('name'=>'C1b_001.JPG'));
		$colstrs=$this->Datastream->listall($col);
		if(isset($colstrs['KML']))
		{
			$kmlfile='/Volumes/data/phptemp/marker.kml';
			$temp=$this->Datastream->content($col,'KML',array(),'raw');
			$colkml=simplexml_load_string($temp);
			$colkml->registerXPathNamespace('k','http://www.opengis.net/kml/2.2');
			$kml=$colkml->xpath('//*[text()="'.$query['upload']['name'].'"]/parent::*');
			$file="<?xml version=\"1.0\" encoding=\"UTF-8\"?><kml xmlns=\"http://www.opengis.net/kml/2.2\">";
			$file.=$kml[0]->asXML()."</kml>";
			$handle=fopen($kmlfile,'w');
			fwrite($handle,$file);
			fclose($handle);
		}
		exit;
	}

}

?>