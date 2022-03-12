<?php
// Element: Display relationship form data fields for update
// File: relupdate.ctp
// Variables: $prepend (used to make the names of fields unique)
// v1.0 SJC 11/10/12
?>
<!-- element: relupdate.ctp -->
<div class="righttextbox">
<?php
	// Called from /relationships/update prepend needed to accomodate multiple updates
	if(!isset($prepend)) { $prepend=''; }
	// Predicate
	echo $this->Form->input($prepend.'predicateTo',array('label'=>false,'options'=>$preds,'div'=>false,'selected'=>$rel['pred'],'onchange'=>'checkObjLit(this.id,\''.$prepend.'\');'));
	echo $this->Form->input($prepend.'predicateFrom',array('type'=>'hidden','value'=>$rel['pred']));
	// Object/Literal
	echo '&nbsp;&nbsp;';
	$obj=$lit="";
	(stristr($rel['obj'],":")) ? $obj=$rel['obj'] : $lit=$rel['obj'];
	($obj=="") ? $ostyle="none" : $ostyle="inline";
	echo $this->Form->input($prepend.'object',array('id'=>$prepend.'object','label'=>false,'options'=>$objs,'div'=>false,'selected'=>$obj,'style'=>'display: '.$ostyle));
	($lit=="") ? $lstyle="none" : $lstyle="inline";
	echo $this->Form->input($prepend.'literal',array('id'=>$prepend.'literal','label'=>false,'value'=>$lit,'div'=>false,'size'=>'8','onfocus'=>'fieldDefault(this.id,\'Literal\');return false;','onblur'=>'fieldDefault(this.id,\'Literal\');return false;','style'=>'display: '.$lstyle));
?>
</div>