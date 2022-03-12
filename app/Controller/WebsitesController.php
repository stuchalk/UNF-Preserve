<?php

/**
 * jafFedora Controller for the ECenter Websites Dataset
 * Version: 1.0 (02/21/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class WebsitesController extends Controller
{
	public $uses=['Website'];

    /**
     * Find all websites
     * @param string $output
     * @return mixed
     */
    public function index($output="array")
    {
        // Get the list of websites (ns and url)
        $data=$this->Website->find("list",["fields"=>["id","name"]]);

        // Set view variables
        $this->set('data',$data);
        $this->set('args',['output'=>$output]);

        // Return data to view/requester
        if($output=="json"):						echo json_encode($data);exit;
        elseif(isset($this->params['requested'])):	return $data;
        elseif($this->params['isAjax']==1):			$this->layout='ajax';
        endif;
    }

    /** Add a website */
    public function add()
    {
        if(!empty($this->data)) {
            $this->Website->create();
            $response=$this->Website->save($this->data);
            if(isset($this->params['requested'])):	return $response;
            elseif($this->params['isAjax']==1):		echo "<pre>";print_r($response);echo "</pre>";exit;
            else:									$this->redirect('/admin/dashboard');
            endif;
        }
    }

    /**
     * View a website
     * @param string $ns
     * @param string $output
     * @return mixed
     */
	public function view($ns="",$output="array")
	{
		// Find this plants record
		$data=$this->Website->find("first",["conditions"=>["ns"=>$ns]]);
		
		// Set view variables
		$this->set('data',$data);
		$this->set('args',['ns'=>$ns,'output'=>$output]);
		
		// Return data to view/requester
		if($output=="json"):						echo json_encode($data);exit;
		elseif(isset($this->params['requested'])):	return $data;
		elseif($this->params['isAjax']==1):			$this->layout='ajax';
		endif;
	}
	
}
