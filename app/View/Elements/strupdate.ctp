<?php
// Element: Display form data fields to update a stream
// File: strupdate.ctp
// Variables: $args, $data (from view)
// v1.0 SJC 12/27/12
$meta=$data['metadata'];$content=$data['content'];
?>
<!-- element: strupdate.ctp -->
<div class="righttextbox">
	<?php
	$fileoptions=array('empty'=>'Select...','local'=>'Local File','upload'=>'Uploaded File','remote'=>'Remote File','text'=>'Text');
	echo $this->Form->create('Datastream',array('enctype' => 'multipart/form-data','action'=>'update/'.$meta['pid'].'/'.$meta['dsID']));
	echo "<p id='errors'></p>";
	echo $this->Form->input('Datastream.query.dsLabel',array('label'=>'Title: ','default'=>$meta['dsLabel'],'size'=>'60','maxLength'=>'60'));
	echo $this->Form->input('Datastream.query.mimeType',array('label'=>'MIME Type: ','default'=>$meta['dsMIME'],'size'=>'50','maxLength'=>'60'));
	echo $this->Form->input('Datastream.query.dsState',array('label'=>'State: ','options'=>array('A'=>'Active','I'=>'Inactive','D'=>'Deleted'),'selected'=>$meta['dsState']));
	echo $this->Form->input('Datastream.query.formatURI',array('label'=>'Format URI: ','default'=>$meta['dsFormatURI'],'size'=>'60','maxLength'=>'60'));
	if(!isset($meta['dsAltID'])) { $meta['dsAltID']=""; }
	echo $this->Form->input('Datastream.query.altIDs',array('label'=>'Alt ID(s): ','default'=>str_replace("_"," ",$meta['dsAltID']),'size'=>'60','maxLength'=>'60'));
	echo $this->Form->hidden('Datastream.query.logMessage',array('value'=>'Updating stream '.$meta['dsID'].' to PID '));
	echo $this->Form->hidden('Datastream.query.controlGroup',array('value'=>$meta['dsControlGroup']));  // Use controlGroup for backend compatibility
	if($meta['dsControlGroup']=='X'||$meta['dsControlGroup']=='M')
	{
		echo $this->Form->input('Datastream.file.source',array('label'=>'File Source: ','selected'=>'empty','options'=>$fileoptions,'onChange'=>'togglevisset(this.options[this.selectedIndex].value,["empty","local","remote","upload","text"]);'));
		echo "<div id=\"empty\" style=\"display: none;\"></div>"; // Dummy field so there is no js error on selecting nothing
		echo $this->Form->input('Datastream.file.local',array('type'=>'file','label'=>'Local Filepath: ','div'=>array('id'=>'local','style'=>'display:none;')));
		echo $this->Form->input('Datastream.file.upload',array('type'=>'file','label'=>'Uploaded File: ','div'=>array('id'=>'upload','style'=>'display:none;')));
		echo $this->Form->input('Datastream.file.remote',array('label'=>'Remote File (at URL): ','div'=>array('id'=>'remote','style'=>'display:none;','size'=>'40')));
		if(!isset($content['content']))	{ $content['content']=''; }
		echo $this->Form->input('Datastream.file.text',array('label'=>false,'type'=>'textarea','rows'=>'10','cols'=>'85','default'=>$content['content'],'div'=>array('id'=>'text','style'=>'display:none;')));
	}
	elseif($meta['dsControlGroup']=='E')
	{
		if($_SERVER['SERVER_ADDR']==Configure::read('jaf.server')||$_SERVER['SERVER_NAME']==Configure::read('jaf.server'))
		{
			unset($fileoptions['upload']);unset($fileoptions['text']);
			echo $this->Form->input('Datastream.file.source',array('label'=>'File Source: ','selected'=>'empty','options'=>$fileoptions,'onChange'=>'togglevisset(this.options[this.selectedIndex].value,["empty","local","remote"]);'));
			echo "<div id=\"empty\" style=\"display: none;\"></div>"; // Dummy field so there is no js error on selecting nothing
			echo $this->Form->input('Datastream.file.local',array('type'=>'file','label'=>'Local Filepath: ','div'=>array('id'=>'local','style'=>'display:none;','size'=>'40')));
			echo $this->Form->input('Datastream.file.remote',array('label'=>'Remote File (at URL): ','div'=>array('id'=>'remote','style'=>'display:none;','size'=>'40')));
		}
		else
		{
			echo $this->Form->hidden('Datastream.file.source',array('value'=>'remote'));
			echo $this->Form->input('Datastream.file.remote',array('label'=>'Remote File (at URL): ','div'=>array('id'=>'remote','size'=>'40')));
		}
	}
	elseif($meta['dsControlGroup']=='R')
	{
		echo $this->Form->hidden('Datastream.file.source',array('value'=>'remote'));
		echo $this->Form->input('Datastream.file.remote',array('label'=>'Remote File (at URL): ','div'=>array('id'=>'remote','size'=>'40')));
	}
	echo $this->Form->end('Update Datastream');
	?>
</div>