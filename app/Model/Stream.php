<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the streams table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Stream extends AppModel
{
	public $belongsTo=['Asset'];
	
	public function exif($id)
	{
	
	}
	
	public function kml()
	{
	
	}

    public function thumb($pid)
    {
        $thumb=$this->find('first',['conditions'=>['pid'=>$pid,'label like '=>'THUMB%'],'order'=>['created'=>'DESC'],'recursive'=>-1]);
        return $thumb['Stream'];
    }
}