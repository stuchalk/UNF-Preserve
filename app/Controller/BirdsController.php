<?php

/**
 * Controller for the Preserve Bird Dataset
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class BirdsController extends AppController
{
	public $uses=['Bird','Asset','Stream','Website'];

    /**
     * CakePHP beforeFilter
     */
    public function beforeFilter()
	{
		$this->Auth->allow('index','view','search','stock');
	}

    /**
     * Get list of all birds
     * @param string $sort
     * @param string $output
     * @return mixed
     */
	public function index($sort="sname",$output="list")
	{
		// Find all bird records
		if($sort=="sname"):	$data=$this->Bird->find('list',['fields'=>['id','fullsname','sfirst'],'order'=>['sname ASC']]);
		else:				$data=$this->Bird->find('list',['fields'=>['id','fullcname','cfirst'],'order'=>['cname ASC']]);
		endif;

		// Get stats
		$stats['group']=$this->Bird->stats('group');
		$stats['family']=$this->Bird->stats('family');
        //debug($data);debug($stats);exit;

        // Set view variables
		$this->set('data',$data);
		$this->set('stats',$stats);
		$this->set('args',['sort'=>$sort,'output'=>$output]);

		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

	/** Add a bird */
	public function add()
	{
		if(!empty($this->data)) {
            $data=$this->data;
            if($this->data['Bird']['ns']!="") { $data['Bird']['url']=$data['Bird']['ns'].":".$data['Bird']['url']; }
            $this->Bird->create();
			$response=$this->Bird->save($data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/birds/view/'.$this->Bird->id);
			endif;
		}
        else {
            $ns=$this->Website->find('list',['conditions'=>['type'=>'bird'],'fields'=>['ns','nsselect']]);

            // Set view variables
            $this->set('ns',$ns);
        }
	}

    /**
     * View a bird
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function view($id="",$output="array")
	{
		// Find this bird record
		$bird=$this->Bird->findById($id);
        $data=$bird['Bird'];

        // Get url or stock photo of bird from website
		if($data['image_url']=="" && $data['url']!="") {
			$data['image_url']=$this->Bird->stockphoto($data['url']);
			$this->Bird->save(['id'=>$id,'image_url'=>$data['image_url']]);
		}

        // Get photos for this bird
		$c=['Stream'=>['conditions'=>['streamid like'=>'Source.%','']]];
		if(strlen($id!=5)) $id=str_pad($id,5,'0',STR_PAD_LEFT);
		$photos=$this->Asset->find('all',['conditions'=>['resource'=>'bird:'.$id,'state'=>'active']]);

        // Get website for this bird
        $website=[];
        if($data['url']!="") {
            list($ns,) = explode(":", $data['url']);
            $website = $this->Website->find('first', ['conditions' => ['ns' => $ns]]);
        }

		// Set view variables
		$this->set('bird',$data);
		$this->set('photos',$photos);
        $this->set('site',$website['Website']);
        $this->set('args',['id'=>$id,'output'=>$output]);

		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

    /**
     * Add a bird image (rewrite)
     */
	public function addimage()
	{

	}

    /**
     * Update a bird
     * @param $id
     * @return mixed
     */
    public function update($id)
	{
		if($this->params['isAjax']==1) { $this->data=['Bird'=>$this->params['form']]; }

		if(!empty($this->data))
		{
			$this->Bird->id=$id;
			$response=$this->Bird->save($this->data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/birds/view/'.$id);
			endif;
		}
		else
		{
			$data=$this->Bird->findById($id);
			$this->set('data',$data);
		}
	}

    /**
     * Get the images for a bird
     * @param string $id
     * @param string $output
     * @return mixed
     */
	public function images($id="",$output="array")
	{
		// Find this birds record
		$data=$this->Bird->findById($id);

		// Get url or stock photo of bird from website
		if($data['Bird']['image_url']=="" && $data['Bird']['url']!="") {
			$data['Bird']['image_url']=$this->Bird->stockphoto($data['Bird']['url']);
			$this->Bird->save(['id'=>$id,'image_url'=>$data['Bird']['image_url']]);
		}

        // Get photos for this bird
        $photos=$this->Preserve->getimages('bird:'.$id);

        // Get collection
        $col=$this->Service->risearch("* <fedora:hasMetadata> 'bird'");

        // Set view variables
		$this->set('data',$data);
        $this->set('col',str_replace("info:fedora/","",$col[0]['s']));
        $this->set('photos',$photos);
		$this->set('args',['id'=>$id,'output'=>$output]);

		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

    /**
     * Search for birds by cname and sname
     */
	public function search()
	{
		$term=$this->request->data['Bird']['term'];
		$results=$this->Bird->find('list',['fields'=>['id','fullcname'],'conditions'=>"MATCH(cname,sname) AGAINST ('+".$term."*' IN BOOLEAN MODE)",'order'=>['fullcname'=>'asc']]);
		$this->set('results',$results);
		$this->set('term',$term);
	}

    /**
     * Access the stockphoto model function
     * @param $id
     */
	public function stock($id)
	{
		// Find this bird record
		$data=$this->Bird->findById($id);

		if($data['Bird']['image_url']=="" && $data['Bird']['url']!="") {
			$data['Bird']['image_url']=$this->Bird->stockphoto($data['Bird']['url']);
			$this->Bird->save(['id'=>$id,'image_url'=>$data['Bird']['image_url']]);
		}
		echo "Done";exit;
	}

    /**
     * Delete a bird
     * @param $id
     */
    public function delete($id)
    {
        $this->Bird->delete($id);
        $this->redirect('/admin/dashboard');
    }

}
