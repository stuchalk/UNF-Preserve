<?php
// Element: Display relationship form data fields
// File: relform.ctp
// Variables: $prepend (used to make the names of fields unique)
// v1.0 SJC 11/10/12
?>
<!-- element: relform.ctp -->
<div class="righttextbox">
<?php
	// Called from /relationships/add and also as part of /object/add (create and end of form are in the view)
	// If called from /object/add need a prepend string to make $streams array for formdata
	if(!isset($prepend)) { $prepend=''; }
	// Predicate
	echo $this->Form->input($prepend.'predicate',array('label'=>false,'options'=>$preds,'div'=>false,'default'=>'','onchange'=>'checkObjLit(this.id,\''.$prepend.'\');'));
	echo '&nbsp;&nbsp;';
	// Object
	echo $this->Form->input($prepend.'object',array('id'=>$prepend.'object','label'=>false,'options'=>$objs,'div'=>false,'default'=>'','style'=>'display: none;'));
	// Literal
	echo $this->Form->input($prepend.'literal',array('id'=>$prepend.'literal','label'=>false,'value'=>'Literal','div'=>false,'default'=>'','style'=>'display: none;','size'=>'8','onfocus'=>'fieldDefault(this.id,\'Literal\');return false;','onblur'=>'fieldDefault(this.id,\'Literal\');return false;'));
?>
</div>