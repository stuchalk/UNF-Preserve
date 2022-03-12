<?php

/**
 * jafFedora Controller for the ECenter Fish Dataset
 * Version: 1.0 (02/21/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class FishController extends AppController
{
	public $uses=['Fish','Asset','Stream','Website'];

    /**
     * CakePHP beforeFilter
     */
    public function beforeFilter()
	{
		$this->Auth->allow('index','view','search','stock','temp');
	}

    /**
     * Get list of all fish
     * @param string $sort
     * @param string $output
     * @return mixed
     */
    public function index($sort="sname",$output="list")
	{
		// Find all fish records
		if($sort=="sname"):	$data=$this->Fish->find('list',['fields'=>['id','fullsname'],'order'=>['sname ASC']]);
		else:				$data=$this->Fish->find('list',['fields'=>['id','fullcname'],'order'=>['cname ASC']]);
		endif;

		// Get stats
		$stats['species']=$this->Fish->stats('species');
		$stats['family']=$this->Fish->stats('family');
		$stats['genus']=$this->Fish->stats('genus');

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

    /** Add a fish */
    public function add()
	{
		if(!empty($this->data)) {
            $data=$this->data;
            if($this->data['Fish']['ns']!="") { $data['Fish']['url']=$data['Fish']['ns'].":".$data['Fish']['url']; }
            $this->Fish->create();
			$response=$this->Fish->save($data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/fish/view/'.$this->Fish->id);
			endif;
		}
        else {
            $ns=$this->Website->find('list',['conditions'=>['type'=>'fish'],'fields'=>['ns','nsselect']]);

            // Set view variables
            $this->set('ns',$ns);
        }
    }

    /**
     * View a fish
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function view($id="",$output="array")
	{
		// Find this fish record
		$fish=$this->Fish->findById($id);
        $data=$fish['Fish'];

        // Get url or stock photo of Invert from website
		if($data['image_url']=="" && $data['url']!="") {
			$data['image_url']=$this->Fish->stockphoto($data['url']);
			$this->Fish->save(['id'=>$id,'image_url'=>$data['image_url']]);
		}

        // Get photos for this fish
		$c=['Stream'=>['conditions'=>['streamid like'=>'Source.%','']]];
		if(strlen($id!=5)) $id=str_pad($id,5,'0',STR_PAD_LEFT);
		$photos=$this->Asset->find('all',['conditions'=>['resource'=>'fish:'.$id,'state'=>'active']]);

		// Get website for this bird
        $website=[];
        if($data['url']!="") {
            list($ns,) = explode(":", $data['url']);
            $website = $this->Website->find('first', ['conditions' => ['ns' => $ns]]);
        }

        // Set view variables
		$this->set('fish',$data);
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
     * Add a fish image
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
			$this->Fobject->isValidCol($query['col'],$this->params);

			// Get fish information
			$result=$this->Fish->findById($query['id']);
			$fish=$result['Fish'];

			// Add images
			// Remove empty upload array entries
			for($x=count($query['upload'])-1;$x>-1;$x--)
			{
				if($query['upload'][$x]['name']=="") { unset($query['upload'][$x]); }
			}
			sort($query['upload']);

            $data = [];
            for ($x = 0; $x < count($query['upload']); $x++) {
                $data[] = $this->Preserve->addimage("fish", $fish, $query['upload'][$x], $query['col'], $query['pcount'] + $x + 1);
            }

			// Set view variables
			$this->set('data',$data);
			$this->set('args',['query'=>$query]);

			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/fish/images/'.$fish['id']);
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
     * Update a fish
     * @param $id
     * @return mixed
     */
    public function update($id)
	{
		if($this->params['isAjax']==1) { $this->data=['Fish'=>$this->params['form']]; }

		if(!empty($this->data))
		{
			$this->Fish->id=$id;
			$response=$this->Fish->save($this->data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/fish/view/'.$id);
			endif;
		}
		else
		{
			$data=$this->Fish->findById($id);
			$this->set('data',$data);
		}
	}

    /**
     * Get the images for a fish
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function images($id="",$output="array")
	{
		// Find this fish record
		$data=$this->Fish->findById($id);

        // Get url or stock photo of fish from website
        if($data['Fish']['image_url']=="" && $data['Fish']['url']!="") {
            $data['Fish']['image_url']=$this->Fish->stockphoto($data['Fish']['url']);
            $this->Fish->save(['id'=>$id,'image_url'=>$data['Fish']['image_url']]);
        }

        // Get photos for this fish
        $photos=$this->Preserve->getimages('fish:'.$id);

        // Get collection
        $col=$this->Service->risearch("* <fedora:hasMetadata> 'fish'");

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
		$term=$this->request->data['Fish']['term'];
		$results=$this->Fish->find('list',['fields'=>['id','fullcname'],'conditions'=>"MATCH(cname,sname) AGAINST ('+".$term."*' IN BOOLEAN MODE)",'order'=>['fullcname'=>'asc']]);
		$this->set('results',$results);
		$this->set('term',$term);
	}

    /**
     * Delete a fish
     * @param $id
     */
    public function delete($id)
    {
        $this->Fish->delete($id);
        $this->redirect('/admin/dashboard');
    }

    /**
     * Access the stockphoto model function
     * @param $id
     */
    public function stock($id)
	{
		// Find this fish record
		$data=$this->Fish->findById($id);
		if($data['Fish']['image_url']=="") {
			$data['Fish']['image_url']=$this->Fish->stockphoto($data['Fish']['url']);
			$this->Fish->save(['id'=>$id,'image_url'=>$data['Fish']['image_url']]);
		}
		echo "Done";exit;
	}

}
