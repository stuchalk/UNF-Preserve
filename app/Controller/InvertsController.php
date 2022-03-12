<?php

/**
 * jafFedora Controller for the ECenter Bird Dataset
 * Version: 1.0 (02/21/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class InvertsController extends AppController
{
	public $uses=['Invert','Asset','Stream','Website'];

    /**
     * CakePHP beforeFilter
     */
    public function beforeFilter()
	{
		$this->Auth->allow('index','view','search','stock','temp');
	}

    /**
     * Get list of all inverts
     * @param string $sort
     * @param string $output
     * @return mixed
     */
    public function index($sort="sname",$output="list")
	{
		// Find all Invert records
		if($sort=="sname"):	$data=$this->Invert->find('list',['fields'=>['id','fullsname','order'],'order'=>['order ASC','sname ASC']]);
		else:				$data=$this->Invert->find('list',['fields'=>['id','fullcname','order'],'order'=>['order ASC','cname ASC']]);
		endif;

		// Get stats
		$stats['order']=$this->Invert->stats('order');
		$stats['family']=$this->Invert->stats('family');
		$stats['genus']=$this->Invert->stats('genus');
		
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

    /** Add an invert */
    public function add()
	{
		if(!empty($this->data))
		{
            $data=$this->data;
            if($this->data['Invert']['ns']!="") { $data['Invert']['url']=$data['Invert']['ns'].":".$data['Invert']['url']; }
            $this->Invert->create();
			$response=$this->Invert->save($data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/inverts/view/'.$this->Invert->id);
			endif;
		}
        else {
            $ns=$this->Website->find('list',['conditions'=>['type'=>'invert'],'fields'=>['ns','nsselect']]);

            // Set view variables
            $this->set('ns',$ns);
        }
    }

    /**
     * View an invert
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function view($id="",$output="array")
	{
		// Find this Invert record
        $invert=$this->Invert->findById($id);
        $data=$invert['Invert'];

		// Get url or stock photo of Invert from website
		if ($data['image_url']=="" && $data['url']!="") {
			$data['image_url']=$this->Invert->stockphoto($data['url']);
			$this->Invert->save(['id'=>$id,'image_url'=>$data['image_url']]);
		}

        // Get photos for this invert
        $c=['Stream'=>['conditions'=>['streamid like'=>'THUMB.%'],'order'=>['created'=>'desc'],'limit'=>1]];
        if(strlen($id!=5)) $id=str_pad($id,5,'0',STR_PAD_LEFT);
        $photos=$this->Asset->find('all',['conditions'=>['resource'=>'invert:'.$id,'state'=>'active'],'contain'=>$c,'recursive'=>1]);

        // Get website for this bird
        $website=[];
        if($data['url']!="") {
            list($ns,) = explode(":", $data['url']);
            $website = $this->Website->find('first', ['conditions' => ['ns' => $ns]]);
        }

        // Set view variables
		$this->set('invert',$data);
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
     * Add an invert image
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

			// Get Invert information
			$result=$this->Invert->findById($query['id']);
			$invert=$result['Invert'];
			
			// Add images
			// Remove empty upload array entries
			for($x=count($query['upload'])-1;$x>-1;$x--)
			{
				if($query['upload'][$x]['name']=="") { unset($query['upload'][$x]); }
			}
			sort($query['upload']);

            $data = [];
            for ($x = 0; $x < count($query['upload']); $x++) {
                $data[] = $this->Preserve->addimage("invert", $invert, $query['upload'][$x], $query['col'], $query['pcount'] + $x + 1);
            }
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',['query'=>$query]);
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/inverts/view/'.$invert['id']);
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
     * Update an invert
     * @param $id
     * @return mixed
     */
    public function update($id)
	{
		if($this->params['isAjax']==1) { $this->data=['Invert'=>$this->params['form']]; }
		
		if(!empty($this->data))
		{
			//echo "<pre>";print_r($this->data);echo "</pre>";exit;
			$this->Invert->id=$id;
			$response=$this->Invert->save($this->data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/Inverts/view/'.$id);
			endif;
		}
		else
		{
			$data=$this->Invert->findById($id);
			$this->set('data',$data);
		}
	}

    /**
     * Get the images for an invert
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function images($id="",$output="array")
	{
		// Find this plants record
		$data=$this->Invert->findById($id);
		
		// Get url or stock photo of Invert from website
		if($data['Invert']['image_url']=="" && $data['Invert']['url']!="")
		{
			$data['Invert']['image_url']=$this->Invert->stockphoto($data['Invert']['url']);
			$this->Invert->save(['id'=>$id,'image_url'=>$data['Invert']['image_url']]);
		}

        // Get photos for this invert
        $photos=$this->Preserve->getimages('invert:'.$id);

        // Get collection
        $col=$this->Service->risearch("* <fedora:hasMetadata> 'invert'");

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
     * Search for inverts by cname and sname
     */
    public function search()
	{
		$term=$this->request->data['Invert']['term'];
		$results=$this->Invert->find('list',['fields'=>['id','fullcname'],'conditions'=>"MATCH(cname,sname) AGAINST ('+".$term."*' IN BOOLEAN MODE)",'order'=>['fullcname'=>'asc']]);
		$this->set('results',$results);
		$this->set('term',$term);
	}

    /**
     * Delete an invert
     * @param $id
     */
    public function delete($id)
    {
        $this->Invert->delete($id);
        $this->redirect('/admin/dashboard');
    }

    /**
     * Access the stockphoto model function
     * @param $id
     */
    public function stock($id)
	{
		// Find this Invert record
		$data=$this->Invert->findById($id);
		if($data['Invert']['image_url']=="")
		{
			$data['Invert']['image_url']=$this->Invert->stockphoto($data['Invert']['url']);
			$this->Invert->save(['id'=>$id,'image_url'=>$data['Invert']['image_url']]);
		}
		echo "Done";exit;
	}

}