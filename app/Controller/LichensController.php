<?php

/**
 * jafFedora Controller for the ECenter Lichen Dataset
 * Version: 1.0 (02/21/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class LichensController extends AppController
{
	public $uses=['Lichen','Asset','Stream','Website'];

    /**
     * CakePHP beforeFilter
     */
    public function beforeFilter()
	{
		$this->Auth->allow('index','view','search','stock','temp');
	}
	
	/**
     * Get a list of all lichen
     * @param string $sort
     * @param string $output
     * @return mixed
     */
	public function index($sort="sname",$output="list")
	{
		// Find all lichen records
		if($sort=="sname"):	$data=$this->Lichen->find('list',['fields'=>['id','fullsname'],'order'=>['sname ASC']]);
		else:				$data=$this->Lichen->find('list',['fields'=>['id','fullcname'],'order'=>['cname ASC']]);
		endif;
		
		// Get stats
		$stats['species']=$this->Lichen->stats('species');
		$stats['family']=$this->Lichen->stats('family');
		$stats['genus']=$this->Lichen->stats('genus');
		
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

    /** Add a lichen */
    public function add()
	{
		if(!empty($this->data))
		{
            $data=$this->data;
            if($this->data['Lichen']['ns']!="") { $data['Lichen']['url']=$data['Lichen']['ns'].":".$data['Lichen']['url']; }
            $this->Lichen->create();
			$response=$this->Lichen->save($data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/lichens/view/'.$this->Lichen->id);
			endif;
		}
        else {
            $ns=$this->Website->find('list',['conditions'=>['type'=>'lichen'],'fields'=>['ns','nsselect']]);

            // Set view variables
            $this->set('ns',$ns);
        }
    }

    /**
     * View a lichen
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function view($id="",$output="array")
	{
		// Find this Lichens record
		$lichen=$this->Lichen->findById($id);
        $data=$lichen['Lichen'];

        // Get url or stock photo of Invert from website
		if($data['image_url']=="" && $data['url']!="") {
			$data['image_url']=$this->Lichen->stockphoto($data['url']);
			$this->Lichen->save(['id'=>$id,'image_url'=>$data['image_url']]);
		}

        // Get photos for this lichen
		$c=['Stream'=>['conditions'=>['streamid like'=>'Source.%','']]];
		if(strlen($id!=5)) $id=str_pad($id,5,'0',STR_PAD_LEFT);
		$photos=$this->Asset->find('all',['conditions'=>['resource'=>'lichen:'.$id,'state'=>'active']]);
		
		// Get website for this bird
        $website=[];
        if($data['url']!="") {
            list($ns,) = explode(":", $data['url']);
            $website = $this->Website->find('first', ['conditions' => ['ns' => $ns]]);
        }

        // Set view variables
		$this->set('lichen',$data);
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
     * Add a lichen image
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

			// Get Lichen information
			$result=$this->Lichen->findById($query['id']);
			$lichen=$result['Lichen'];
			
			// Add images
			// Remove empty upload array entries
			for($x=count($query['upload'])-1;$x>-1;$x--)
			{
				if($query['upload'][$x]['name']=="") { unset($query['upload'][$x]); }
			}
			sort($query['upload']);

            $data = [];
            for ($x = 0; $x < count($query['upload']); $x++) {
                $data[] = $this->Preserve->addimage("lichen", $lichen, $query['upload'][$x], $query['col'], $query['pcount'] + $x + 1);
            }

			// Set view variables
			$this->set('data',$data);
			$this->set('args',['query'=>$query]);
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/lichens/images/'.$lichen['id']);
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
     * Update a lichen
     * @param $id
     * @return mixed
     */
    public function update($id)
	{
		if($this->params['isAjax']==1) { $this->data=['Lichen'=>$this->params['form']]; }
		
		if(!empty($this->data))
		{
			$this->Lichen->id=$id;
			$response=$this->Lichen->save($this->data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/lichens/view/'.$id);
			endif;
		}
		else
		{
			$data=$this->Lichen->findById($id);
			$this->set('data',$data);
		}
	}

    /**
     * Get the images for a lichen
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function images($id="",$output="array")
	{
		// Find this Lichens record
		$data=$this->Lichen->findById($id);

        // Get url or stock photo of bird from website
        if($data['Lichen']['image_url']=="" && $data['Lichen']['url']!="") {
            $data['Lichen']['image_url']=$this->Lichen->stockphoto($data['Bird']['url']);
            $this->Lichen->save(['id'=>$id,'image_url'=>$data['Lichen']['image_url']]);
        }

        // Get photos for this lichen
        $photos=$this->Preserve->getimages('lichen:'.$id);

        // Get collection
        $col=$this->Service->risearch("* <fedora:hasMetadata> 'lichen'");

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
     * Search for lichens by cname and sname
     */
	public function search()
	{
		$term=$this->request->data['Lichen']['term'];
		$results=$this->Lichen->find('list',['fields'=>['id','fullcname'],'conditions'=>"MATCH(cname,sname) AGAINST ('+".$term."*' IN BOOLEAN MODE)",'order'=>['fullcname'=>'asc']]);
		$this->set('results',$results);
		$this->set('term',$term);
	}

    /**
     * Delete a lichen
     * @param $id
     */
    public function delete($id)
    {
        $this->Lichen->delete($id);
        $this->redirect('/admin/dashboard');
    }

    /**
     * Access the stock photo model function
     * @param $id
     */
	public function stock($id)
	{
		// Find this lichen record
		$data=$this->Lichen->findById($id);
		if($data['Lichen']['image_url']=="" && $data['Lichen']['url']!="")
		{
			$data['Lichen']['image_url']=$this->Lichen->stockphoto($data['Lichen']['url']);
			$this->Lichen->save(['id'=>$id,'image_url'=>$data['Lichen']['image_url']]);
		}
		echo "Done";exit;
	}

}