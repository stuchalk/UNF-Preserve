<?php

/**
 * jafFedora Controller for the ECenter Photo Dataset
 * Version: 1.0 (02/21/15)
 * Copyright 2011-2015 Stuart J. Chalk
 */
class PhotosController extends AppController
{
    public $uses=['Photo','Preserve','Stream'];

    /**
     * Cakephp beforeFilter
     */
    public function beforeFilter()
    {
        $this->Auth->allow();
    }

    /**
     * View all images for info pages
     * @param string $output
     * @return array
     */
    public function index($output="array")
    {
        $types=['burn','exotic','habitat','special'];
        $allimages=[];

        // Get pids of photos
        foreach($types as $type) { $allimages[$type]=$this->Preserve->getimages($type,"Available"); }

        // Get labels of photos for accessing on webpages
        $labels=$this->Photo->find('list',['fields'=>['pid','label']]);

        // Set view variables
        $this->set('allimages',$allimages);
        $this->set('labels',$labels);
        $this->set('types',$types);

        // Return data to view/requester
        if($output=="json"):						echo json_encode($allimages);exit;
        elseif(isset($this->params['requested'])):	return $allimages;
        elseif($this->params['isAjax']==1):			$this->layout='ajax';
        endif;
    }

    /**
     * Upload images for webpages
     * @param string $col
     * @param string $output
     */
    public function add($col="",$output="redirect")
    {
        // Get Fedora request query parameters (via GET or POST)
        $query=$this->Fobject->getQuery($this);

        // Get data
        if(!empty($query))
        {
            // Get collection information
            $this->Fobject->isValidCol($query['col'],$this->params);
            $pcount=$query['count'];

            // Add images
            // Remove empty upload array entries
            for($x=count($query['upload'])-1;$x>-1;$x--)
            {
                if($query['upload'][$x]['name']=="") { unset($query['upload'][$x]); }
            }
            sort($query['upload']);

            $data = [];
            for ($x = 0; $x < count($query['upload']); $x++) {
                $type=$query['upload'][$x]['itype'];
                $data[] = $this->Preserve->addimage($type, ['cname'=>ucfirst($type),'sname'=>ucfirst($type)], $query['upload'][$x], $query['col'], $pcount[$type] + $x + 1);
            }

            // Set view variables
            $this->set('data',$data);
            $this->set('args',['query'=>$query]);

            // Return data to view/requester
            if($output=="json"):						echo json_encode($data);exit;
            elseif($output=="redirect"):				$this->redirect('/photos');
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
     * Show photo (send to browser)
     * @param $label
     */
    public function show($label)
    {
        $pid=$this->Photo->find('list',['conditions'=>['label'=>$label],'fields'=>['label','pid']]);
        $thumb=$this->Stream->thumb($pid[$label]);
        header("Content-Type: ".$thumb['mimetype']);
        header('Content-Length: '.$thumb['size']);
        header('Content-Disposition: inline; filename="'.$label.'.jpg"');
        ob_clean();
        flush();
        readfile(WWW_ROOT.$thumb['path']);
        exit;
    }
}
