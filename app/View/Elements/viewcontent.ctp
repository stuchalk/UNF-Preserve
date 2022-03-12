<?php
// Element: Display form data fields to update a stream
// File: viewcontent.ctp
// Variables: $args, $data (from view)
// v1.0 SJC 12/27/12
$path=Configure::read('jaf.path');
?>
<!-- element: viewcontent.ctp -->
<div class="righttextbox">
	<?php
	if($args['output']=="array"||$args['output']=="modal")
	{
		// Display media (or icon) in browser
		if($data['metadata']['dsControlGroup']=='X')
		{
			echo $this->element('viewiframe',array('url'=>$path.'/services/saxon/'.$args['pid'].'*'.$args['dsid'].'/test:xslt*XSLT'));
		}
		elseif($data['metadata']['dsControlGroup']=='M')
		{
			if(stristr($data['metadata']['dsMIME'],'image')):		echo $this->element('viewimage',array('url'=>$data['content']['exturl']));
			elseif(stristr($data['metadata']['dsMIME'],'openxml')):	echo $this->element('googleviewer',array('url'=>$data['content']['exturl']));
			elseif(stristr($data['metadata']['dsMIME'],'xml')):		echo $this->element('viewmarkup',array('data'=>$data['content']['content']));
			elseif(stristr($data['metadata']['dsMIME'],'pdf')):		echo $this->element('googleviewer',array('url'=>$data['content']['exturl']));
			elseif(isset($data['content']['icon'])):				echo $this->element('viewimage',array('url'=>$path.'/img/'.$data['content']['icon']));
			else:													echo $data['content']['content'];
			endif;
		}
		elseif($data['metadata']['dsControlGroup']=='E')
		{
			echo $this->element('viewiframe',array('url'=>$data['metadata']['dsLocation'],'height'=>'500'));
		}
		elseif($data['metadata']['dsControlGroup']=='R')
		{
			if(stristr($data['metadata']['dsMIME'],'image')): echo $this->element('viewimage',array('url'=>$data['metadata']['dsLocation']));
			endif;
		}
	}
	else
	{
		echo $this->element('viewmarkup',array('data'=>$data));
	}
	?>
</div>