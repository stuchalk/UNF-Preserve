<div class="left">
	<h2>Add Relationship</h2>
	<?php echo $this->element('relmeta'); ?>
	<?php echo $this->element('reladmin'); ?>
</div>
<div class="right">
	<h2>New Relationship</h2>
	<?php
	// Predicate list
	foreach(Configure::read('jaf.preds') as $key=>$rel)		{ $temp[$key]='jaf: '.$rel; }
	foreach(Configure::read('fed.relsext') as $key=>$rel)	{ $temp[$key]='relsext: '.$rel; }
	$preds=array(''=>'Choose Predicate...')+$temp;
	// Object list
	list($ns,)=explode(":",$args['pid'],2);
	$temp=$this->requestAction('/objects/fsearch/'.$ns.':*');
	$objects=array(''=>'Choose Object (from the \''.$ns.'\' namespace)')+$temp['results'];$objs=array();
	foreach($objects as $pid => $title) { (strlen($title)>50) ? $objs[$pid]=substr($title,0,50).'...' : $objs[$pid]=substr($title,0,50); }
	// Add form
	echo $this->Form->create('Relationship',array('enctype' => 'multipart/form-data','action'=>'add/'.$args['pid']));
	echo $this->element('relform',array('prepend'=>'Relationship.','preds'=>$preds,'objs'=>$objs));
	echo $this->Form->end('Add Relationship');
	?>
	<h3>Existing Relationships</h3>
	<?php echo $this->element('relsext'); ?>
</div>