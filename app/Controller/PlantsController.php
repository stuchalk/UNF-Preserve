<?php

/**
 * jafFedora Controller for the ECenter Plant Dataset
 * Version: 1.0 (02/21/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class PlantsController extends AppController
{
	public $uses=['Plant','Asset','Stream','Website'];

    /**
     * CakePHP beforeFilter
     */
    public function beforeFilter()
	{
		$this->Auth->allow('index','view','search','stock');
	}

    /**
     * Get list of all plants
     * @param string $sort
     * @param string $output
     * @return mixed
     */
    public function index($sort="sname",$output="list")
	{
		// Find all plant records
		if($sort=="sname"):	$data=$this->Plant->find('list',['fields'=>['id','fullsname','sfirst'],'order'=>['sname ASC']]);
		else:				$data=$this->Plant->find('list',['fields'=>['id','fullcname','cfirst'],'order'=>['cname ASC']]);
		endif;

		// Get stats
		$stats['group']=$this->Plant->stats('group');
		$stats['family']=$this->Plant->stats('family');
		$stats['genus']=$this->Plant->stats('genus');
		
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

    /** Add a plant */
    public function add()
	{
		if(!empty($this->data)) {
            $data=$this->data;
            if($this->data['Plant']['ns']!="") { $data['Plant']['url']=$data['Plant']['ns'].":".$data['Plant']['url']; }
            $this->Plant->create();
			$response=$this->Plant->save($data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/plants/view/'.$this->Plant->id);
			endif;
		}
        else {
            $ns=$this->Website->find('list',['conditions'=>['type'=>'plant'],'fields'=>['ns','nsselect']]);

            // Set view variables
            $this->set('ns',$ns);
        }
    }

    /**
     * View a plant
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function view($id="",$output="array")
	{
		// Find this plants record
		$plant=$this->Plant->findById($id);
        $data=$plant['Plant'];

        // Get url or stock photo of Invert from website
		if($data['image_url']=="" && $data['url']!="") {
			$data['image_url']=$this->Plant->stockphoto($data['url']);
			$this->Plant->save(['id'=>$id,'image_url'=>$data['image_url']]);
		}

        // Get photos for this plant
        $c=['Stream'=>['conditions'=>['streamid like'=>'THUMB.%'],'order'=>['created'=>'desc'],'limit'=>1]];
        if(strlen($id!=5)) $id=str_pad($id,5,'0',STR_PAD_LEFT);
        $photos=$this->Asset->find('all',['conditions'=>['resource'=>'plant:'.$id,'state'=>'active'],'contain'=>$c,'recursive'=>1]);
        //debug($photos);exit;

        // Get website for this bird
        $website=[];
        if($data['url']!="") {
            list($ns,) = explode(":", $data['url']);
            $website = $this->Website->find('first', ['conditions' => ['ns' => $ns]]);
        }

        // Set view variables
		$this->set('plant',$data);
		$this->set('photos',$photos);
        if(isset($website['Website'])) { $this->set('site',$website['Website']); }
        $this->set('args',['id'=>$id,'output'=>$output]);
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

    /**
     * Add a plant image
     * @param string $col
     * @param string $output
     */
    public function addimage($col="",$output="redirect")
	{
		// Get Fedora request query parameters (via GET or POST)
		$query=$this->Fobject->getQuery($this);
		
		// Get data
		if(!empty($query))
		{
			// Get collection information
			$this->Collection->isValidCol($query['col'],$this->params);

			// Get plant information
			$result=$this->Plant->findById($query['id']);
			$plant=$result['Plant'];
			
			// Add images
			// Remove empty upload array entries
			for($x=count($query['upload'])-1;$x>-1;$x--) {
				if($query['upload'][$x]['name']=="") { unset($query['upload'][$x]); }
			}
			sort($query['upload']);

            $data = [];
            for ($x = 0; $x < count($query['upload']); $x++) {
                $data[] = $this->Preserve->addimage("plant", $plant, $query['upload'][$x], $query['col'], $query['pcount'] + $x + 1);
            }

            // Set view variables
			$this->set('data',$data);
			$this->set('args',['query'=>$query]);
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/plants/images/'.$plant['id']);
			elseif(isset($this->params['requested'])):	return $data;
			elseif($this->params['isAjax']==1):			$this->layout='ajax';
			endif;
		}
		else
		{
			// Get data about current collections
			if($col!=""):	$data=$this->Fobject->view($col);
			else:			$data=$this->Collection->index();
			endif;
		
			// Set view variable
			$this->set('data',$data);
			$this->set('args',['col'=>$col]);
			
			// Return to view
			if($this->params['isAjax']==1) { $this->layout='ajax'; }
		}
	}

    /**
     * Update a plant
     * @param $id
     * @return mixed
     */
    public function update($id)
	{
		if($this->params['isAjax']==1) { $this->data=['Plant'=>$this->params['form']]; }
		
		if(!empty($this->data))
		{
			$this->Plant->id=$id;
			$response=$this->Plant->save($this->data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/plants/view/'.$id);
			endif;
		}
		else
		{
			$data=$this->Plant->findById($id);
			$this->set('data',$data);
		}
	}

    /**
     * Get the images for a plant
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function images($id="",$output="array")
	{
		// Find this plants record
		$data=$this->Plant->findById($id);

        // Get url or stock photo of bird from website
        if($data['Plant']['image_url']=="" && $data['Plant']['url']!="") {
            $data['Plant']['image_url']=$this->Bird->stockphoto($data['Plant']['url']);
            $this->Plant->save(['id'=>$id,'image_url'=>$data['Plant']['image_url']]);
        }

        // Get photos for this plant
        $photos=$this->Preserve->getimages('plant:'.$id);

        // Get collection
        $col=$this->Service->risearch("* <fedora:hasMetadata> 'plant'");

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
     * Search for plants by cname and sname
     */
    public function search()
	{
		$term=$this->request->data['Plant']['term'];
		$results=$this->Plant->find('list',['fields'=>['id','fullcname'],'conditions'=>"MATCH(cname,sname) AGAINST ('+".$term."*' IN BOOLEAN MODE)",'order'=>['fullcname'=>'asc']]);
		$this->set('results',$results);
		$this->set('term',$term);
	}

    /**
     * Delete a plant
     * @param $id
     */
    public function delete($id)
    {
        $this->Plant->delete($id);
        $this->redirect('/admin/dashboard');
    }

    /**
     * Access the stockphoto model function
     * @param $id
     */
    public function stock($id)
	{
		// Find this plant record
		$data=$this->Plant->findById($id);
		if($data['Plant']['image_url']=="")
		{
			$data['Plant']['image_url']=$this->Plant->stockphoto($data['Plant']['url']);
			$this->Plant->save(['id'=>$id,'image_url'=>$data['Plant']['image_url']]);
		}
		echo "Done";exit;
	}
	
}