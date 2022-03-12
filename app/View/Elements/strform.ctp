<?php
// Element: Display form data fields for a new stream
// File: strform.ctp
// Variables: $prepend (used to make the names of fields unique), $pid
// v1.0 SJC 12/27/12
?>
<!-- element: strform.ctp -->
<div class="righttextbox">
<?php
	if(!isset($prepend)) { $prepend='Datastream.'; }
	$idpre=str_replace('.','',$prepend);
	$fileoptions=array('Empty'=>'File Source...','Local'=>'Local File','Upload'=>'Uploaded File','Remote'=>'Remote File','Text'=>'Text');
	if($_SERVER['REMOTE_ADDR']!='139.62.52.13')		{ unset($fileoptions['Local']); }
	if(isset($pid)):	echo $this->Form->input($prepend.'dsID',array('label'=>'ID: ','onkeyup'=>'checkdsid(\''.$args['pid'].'\',this.id);','div'=>false));
	else:				echo $this->Form->input($prepend.'dsID',array('label'=>'ID: ','div'=>false));
	endif;
	echo '&nbsp;&nbsp;'.$this->Form->input($prepend.'query.dsLabel',array('label'=>'Title: ','div'=>false));
	echo $this->Form->hidden($prepend.'query.logMessage',array('value'=>'Adding stream'));
	echo $this->Form->hidden($prepend.'query.checksumType',array('value'=>'DISABLED'));
	echo '&nbsp;&nbsp;'.$this->Form->input($prepend.'source',array('label'=>false,'selected'=>'empty','options'=>$fileoptions,'onChange'=>'togglevisset("'.$idpre.'"+this.options[this.selectedIndex].value,["'.$idpre.'Empty","'.$idpre.'Local","'.$idpre.'Remote","'.$idpre.'Upload","'.$idpre.'Text"]);','div'=>false));
	echo $this->Form->input($prepend.'upload',array('type'=>'file','label'=>'Uploaded File: ','div'=>array('id'=>$idpre.'Upload','style'=>'display:none;','name'=>'upload')));
	// Hidden fields that can be seen by selecting a value in the 'upload' select above
	echo "<div id=\"".$idpre."Empty\" style=\"display: none;\"></div>"; // Dummy field so there is no js error on selecting nothing
	echo $this->Form->input($prepend.'local',array('label'=>'Local File: ','div'=>array('id'=>$idpre.'Local','style'=>'display:none;')));
	echo $this->Form->input($prepend.'remote',array('label'=>'Remote File (URL): ','div'=>array('id'=>$idpre.'Remote','style'=>'display:none;')));
	echo "<div id=\"".$idpre."Text\" style=\"display: none;\">";
	echo $this->Form->input($prepend.'text',array('label'=>false,'type'=>'textarea','rows'=>'10','cols'=>'75','div'=>false));
	echo $this->Form->input($prepend.'textcheck',array('label'=>'If this is XML, click here if you want it to be \'Inline\' rather than a managed file','type'=>'checkbox'));
	echo "</div>";
?>
</div>