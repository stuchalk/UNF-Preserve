<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the websites table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Website extends AppModel
{
	public $virtualFields = ['nsselect'=>'CONCAT(`Website.url`,"<id>")'];
	
	public function listall()
	{
		return $this->find('list',['fields'=>['ns','url']]);
	}

}

?>