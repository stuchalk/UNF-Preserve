<?php

/**
 * Controller for the streams table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class StreamsController extends AppController
{

    /**
     * CakePHP beforeFilter
     */
    public function beforeFilter()
    {
        $this->Auth->allow('thumb');
    }


    public function thumb($id)
    {
		$stream=$this->Stream->find('first',['conditions'=>['id'=>$id],'recursive'=>-1]);
        $thumb=$stream['Stream'];
		header("Content-Type: ".$thumb['mimetype']);
		header('Content-Length: '.$thumb['size']);
		header('Content-Disposition: inline; filename="image.jpg"');
		ob_clean();
		flush();
		readfile(WWW_ROOT.$thumb['path']);
		exit;
    }

}