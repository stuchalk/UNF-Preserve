<?php
/**
* jafFedora Controller for the ECenter Websites Dataset
 * Version: 1.0 (10/16/13)
 * Copyright 2011-2013 Stuart J. Chalk
 */

class WebpagesController extends AppController
{
	public $uses=['Webpage'];

    public function beforeFilter()
    {
        $this->Auth->allow();
    }

    public function index($output="array")
	{
		// Get the list of webpages
		$data=$this->Webpage->find('list',array('fields'=>array('id','title')));
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',array('output'=>$output));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
		
	}

	public function edit($id,$loc="dash",$output="array")
	{
		// Get the webpage for editing
		$data=$this->Webpage->find('first',array('conditions'=>array('id'=>$id)));
		
		// Set view variables
		$this->set('data',$data['Webpage']);
		$this->set('loc',$loc);
		$this->set('args',array('id'=>$id));
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}
	
	public function update($loc)
	{
		$this->Webpage->save($this->request->data);
		$id=str_pad($this->request->data['Webpage']['id'],2,'0',STR_PAD_LEFT);
		if($loc=='page'):		$page=$this->Webpage->find('list',array('conditions'=>array('id'=>$id),'fields'=>array('id','label')));
								return $this->redirect('http://preserve.unf.edu/'.$page[$id]); // Allows for routing to work correctly
		elseif($loc=='dash'):	return $this->redirect('/admin/dashboard');
		endif;
	}
	
	public function display($id,$output="array")
	{
		// Get the webpage for editing
        if(is_numeric($id)) {
            $data=$this->Webpage->find('first',['conditions'=>['id'=>$id]]);
        } else {
            $data=$this->Webpage->find('first',['conditions'=>['label'=>$id]]);
        }

		// Set view variables
		$this->set('data',$data['Webpage']);
		$this->set('args',['id'=>$id]);
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}

    public function show($label,$output="array")
    {
        // Get the webpage for editing
        $data=$this->Webpage->find('first',array('conditions'=>array('label'=>$label)));

        // Set view variables
        $this->set('data',$data['Webpage']);
        $this->set('args',array('id'=>$data['Webpage']['id']));

        // Return data to view/requester
        if($output=="json"):						echo json_encode($data);exit;
        elseif(isset($this->params['requested'])):	return $data;
        elseif($this->params['isAjax']==1):			$this->layout='ajax';
        else:                                       $this->render('display');
        endif;

    }

    public function test()
    {
        echo "<pre>";print_r($this->Webpage->getlabels());echo "</pre>";exit;
    }
}