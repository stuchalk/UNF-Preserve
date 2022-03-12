<?php

/**
 * jafFedora Controller for the ECenter Mammal Dataset
 * Version: 1.0 (02/21/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class MammalsController extends AppController
{
	public $uses=['Mammal','Asset','Stream','Website'];

    /**
     * CakePHP beforeFilter
     */
    public function beforeFilter()
	{
		$this->Auth->allow('index','view','search','stock');
	}

    /**
     * Get list of all mammals
     * @param string $sort
     * @param string $output
     * @return mixed
     */
    public function index($sort="sname",$output="list")
	{
		// Find all mammal records
		if($sort=="sname"):	$data=$this->Mammal->find('list',['fields'=>['id','fullsname'],'order'=>['sname ASC']]);
		else:				$data=$this->Mammal->find('list',['fields'=>['id','fullcname'],'order'=>['cname ASC']]);
		endif;
		
		// Get stats
		$stats['species']=$this->Mammal->stats('species');
		$stats['family']=$this->Mammal->stats('family');
		$stats['genus']=$this->Mammal->stats('genus');
		
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
		if(!empty($this->data))
		{
            $data=$this->data;
            if($this->data['Mammal']['ns']!="") { $data['Mammal']['url']=$data['Mammal']['ns'].":".$data['Mammal']['url']; }
            $this->Mammal->create();
			$response=$this->Mammal->save($data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/mammals/view/'.$this->Mammal->id);
			endif;
		}
        else {
            $ns=$this->Website->find('list',['conditions'=>['type'=>'mammal'],'fields'=>['ns','nsselect']]);

            // Set view variables
            $this->set('ns',$ns);
        }
    }

    /**
     * View a mammal
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function view($id="",$output="array")
	{
		// Find this mammal record
		$mammal=$this->Mammal->findById($id);
        $data=$mammal['Mammal'];

        // Get url or stock photo of Invert from website
		//if($data['image_url']=="" && $data['url']!="") {
		//	$data['image_url']=$this->Mammal->stockphoto($data['url']);
			//$this->Mammal->save(['id'=>$id,'image_url'=>$data['image_url']]);
		//}

        // Get photos for this mammal
		$c=['Stream'=>['conditions'=>['streamid like'=>'Source.%','']]];
		if(strlen($id!=5)) $id=str_pad($id,5,'0',STR_PAD_LEFT);
		$photos=$this->Asset->find('all',['conditions'=>['resource'=>'mammal:'.$id,'state'=>'active']]);
		
		// Get website for this bird
        $website=[];
        if($data['url']!="") {
            list($ns,) = explode(":", $data['url']);
            $website = $this->Website->find('first', ['conditions' => ['ns' => $ns]]);
        }

        // Set view variables
		$this->set('mammal',$data);
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
     * Add a mammal image
     * @param string $col
     * @param string $output
     */
    public function addimage($col="",$output="redirect")
	{
		// Get Fedora request query parameters (via GET or POST)
		$query=$this->Fobject->getQuery($this);

		// Get data
		if(!empty($query)) {
			// Get collection information
            $this->Fobject->isValidCol($query['col'],$this->params);
			
			// Get mammal information
			$result=$this->Mammal->findById($query['id']);
			$mammal=$result['Mammal'];
			
			// Add images
			// Remove empty upload array entries
			for($x=count($query['upload'])-1;$x>-1;$x--) {
				if($query['upload'][$x]['name']=="") {
                    unset($query['upload'][$x]);
                }
			}
			sort($query['upload']);

            $data=[];
			for($x=0;$x<count($query['upload']);$x++) {
				$data[]=$this->Preserve->addimage("mammal",$mammal,$query['upload'][$x],$query['col'],$query['pcount']+$x+1);
			}
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',['query'=>$query]);
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/mammals/images/'.$mammal['id']);
			elseif(isset($this->params['requested'])):	return $data;
			elseif($this->params['isAjax']==1):			$this->layout='ajax';
			endif;
		}
		else
		{
            // Currently not used as addimage is called from another page (admin/images)

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
     * Update a mammal
     * @param $id
     * @return mixed
     */
    public function update($id)
	{
		if($this->params['isAjax']==1) { $this->data=['Mammal'=>$this->params['form']]; }
		
		if(!empty($this->data))
		{
			$this->Mammal->id=$id;
			$response=$this->Mammal->save($this->data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/mammals/view/'.$id);
			endif;
		}
		else
		{
			$data=$this->Mammal->findById($id);
			$this->set('data',$data);
		}
	}

    /**
     * Get the images for a mammal
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function images($id="",$output="array")
	{
		// Find this mammal record
		$data=$this->Mammal->findById($id);

        // Get url or stock photo of bird from website
        if($data['Mammal']['image_url']=="" && $data['Mammal']['url']!="") {
            $data['Mammal']['image_url']=$this->Mammal->stockphoto($data['Mammal']['url']);
            $this->Mammal->save(['id'=>$id,'image_url'=>$data['Mammal']['image_url']]);
        }

        // Get photos for this mammal
        $photos=$this->Preserve->getimages('mammal:'.$id);

        // Get collection
        $col=$this->Service->risearch("* <fedora:hasMetadata> 'mammal'");

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
		$term=$this->request->data['Mammal']['term'];
		$results=$this->Mammal->find('list',['fields'=>['id','fullcname'],'conditions'=>"MATCH(cname,sname) AGAINST ('+".$term."*' IN BOOLEAN MODE)",'order'=>['fullcname'=>'asc']]);
		$this->set('results',$results);
		$this->set('term',$term);
	}

    /**
     * Delete a mammal
     * @param $id
     */
    public function delete($id)
    {
        $this->Mammal->delete($id);
        $this->redirect('/admin/dashboard');
    }

    /**
     * Access the stockphoto model function
     * @param $id
     */
    public function stock($id)
	{
		// Find this mammal record
		$data=$this->Mammal->findById($id);
		if($data['Mammal']['image_url']=="") {
			$data['Mammal']['image_url']=$this->Mammal->stockphoto($data['Mammal']['url']);
			$this->Mammal->save(['id'=>$id,'image_url'=>$data['Mammal']['image_url']]);
		}
		echo "Done";exit;
	}
	
}