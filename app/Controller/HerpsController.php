<?php

/**
 * jafFedora Controller for the ECenter Fish Dataset
 * Version: 1.0 (02/21/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class HerpsController extends AppController
{
	public $uses=['Herp','Asset','Stream','Website'];

    /**
     * CakePHP beforeFilter
     */
    public function beforeFilter()
	{
		$this->Auth->allow('index','view','search','stock','temp');
	}

    /**
     * Get list of all herps
     * @param string $sort
     * @param string $output
     * @return mixed
     */
    public function index($sort="sname",$output="list")
	{
		// Find all herp records
        if($sort=="sname"):	$data=$this->Herp->find('list',['fields'=>['id','fullsname'],'order'=>['sname ASC']]);
        else:				$data=$this->Herp->find('list',['fields'=>['id','fullcname'],'order'=>['cname ASC']]);
        endif;

        // Get stats
		$stats['genus']=$this->Herp->stats('genus');
		$stats['family']=$this->Herp->stats('family');
		$stats['order']=$this->Herp->stats('order');
		
		// Set view variables
		$this->set('data',$data);
		$this->set('stats',$stats);
		$this->set('args',['output'=>$output]);
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

    /** Add a herp */
    public function add()
	{
		if(!empty($this->data)) {
            $data=$this->data;
            if($this->data['Herp']['ns']!="") { $data['Herp']['url']=$data['Herp']['ns'].":".$data['Herp']['url']; }
            $this->Herp->create();
			$response=$this->Herp->save($data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/herps/view/'.$this->Herp->id);
			endif;
		}
        else {
            $ns=$this->Website->find('list',['conditions'=>['type'=>'herp'],'fields'=>['ns','nsselect']]);

            // Set view variables
            $this->set('ns',$ns);
        }
    }

    /**
     * View a herp
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function view($id="",$output="array")
	{
		// Find this herps record
		$herp=$this->Herp->findById($id);
        $data=$herp['Herp'];

        // Get url or stock photo of herp from website
		if($data['image_url']=="" && $data['url']!="") {
			$data['image_url']=$this->Herp->stockphoto($data['url']);
			$this->Herp->save(['id'=>$id,'image_url'=>$data['image_url']]);
		}

        // Get photos for this herp
		$c=['Stream'=>['conditions'=>['streamid like'=>'Source.%','']]];
		if(strlen($id!=5)) $id=str_pad($id,5,'0',STR_PAD_LEFT);
		$photos=$this->Asset->find('all',['conditions'=>['resource'=>'herp:'.$id,'state'=>'active']]);
		
		// Get website for this herp
        $website=[];
        if($data['url']!="") {
            list($ns,) = explode(":", $data['url']);
            $website = $this->Website->find('first', ['conditions' => ['ns' => $ns]]);
        }

        // Set view variables
		$this->set('herp',$data);
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
     * Add a herp image
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

			// Get herp information
			$result=$this->Herp->findById($query['id']);
			$herp=$result['Herp'];
			
			// Add images
			// Remove empty upload array entries
			for($x=count($query['upload'])-1;$x>-1;$x--) {
				if($query['upload'][$x]['name']=="") { unset($query['upload'][$x]); }
			}
			sort($query['upload']);

            $data = [];
            for ($x = 0; $x < count($query['upload']); $x++) {
                $data[] = $this->Preserve->addimage("herp", $herp, $query['upload'][$x], $query['col'], $query['pcount'] + $x + 1);
            }
			
			// Set view variables
			$this->set('data',$data);
			$this->set('args',['query'=>$query]);
			
			// Return data to view/requester
			if($output=="json"):						echo json_encode($data);exit;
			elseif($output=="redirect"):				$this->redirect('/herps/view/'.$herp['id']);
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
     * Update a herp
     * @param $id
     * @return mixed
     */
    public function update($id)
	{
		if($this->params['isAjax']==1) { $this->data=['Herp'=>$this->params['form']]; }
		
		if(!empty($this->data))
		{
			$this->Herp->id=$id;
			$response=$this->Herp->save($this->data);
			if(isset($this->params['requested'])):	return $response;
			elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
			else:									$this->redirect('/herps/view/'.$id);
			endif;
		}
		else
		{
			$data=$this->Herp->findById($id);
			$this->set('data',$data);
		}
	}

    /**
     * Get the images for a herp
     * @param string $id
     * @param string $output
     * @return mixed
     */
    public function images($id="",$output="array")
    {
        // Find this herps record
        $data=$this->Herp->findById($id);

        // Get url or stock photo of herp from website
        if($data['Herp']['image_url']=="" && $data['Herp']['url']!="") {
            $data['Herp']['image_url']=$this->Herp->stockphoto($data['Herp']['url']);
            $this->Herp->save(['id'=>$id,'image_url'=>$data['Herp']['image_url']]);
        }

        // Get photos for this herp
        $photos=$this->Preserve->getimages('herp:'.$id);

        // Get collection
        $col=$this->Service->risearch("* <fedora:hasMetadata> 'herp'");

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
     * Search for herps by cname and sname
     */
    public function search()
	{
		$term=$this->request->data['Herp']['term'];
		$results=$this->Herp->find('list',['fields'=>['id','fullcname'],'conditions'=>"MATCH(cname,sname) AGAINST ('+".$term."*' IN BOOLEAN MODE)",'order'=>['fullcname'=>'asc']]);
		$this->set('results',$results);
		$this->set('term',$term);
	}

    /**
     * Delete a herp
     * @param $id
     */
    public function delete($id)
    {
        $this->Herp->delete($id);
        $this->redirect('/admin/dashboard');
    }

    /**
     * Access the stockphoto model function
     * @param $id
     */
    public function stock($id)
	{
		// Find this Invert record
		$data=$this->Herp->findById($id);
		if($data['Herp']['image_url']=="")
		{
			$data['Herp']['image_url']=$this->Herp->stockphoto($data['Herp']['url']);
			$this->Herp->save(['id'=>$id,'image_url'=>$data['Herp']['image_url']]);
		}
		echo "Done";exit;
	}

}