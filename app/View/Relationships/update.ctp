<div class="left">
	<h2>Update Relationships</h2>
	<?php echo $this->element('relmeta'); ?>
	<?php echo $this->element('reladmin'); ?>
</div>
<div class="right">
	<h2>Edit</h2>
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
	// Update form
	$c=0;
	echo $this->Form->create('Relationship',array('action'=>'/update/'.$args['pid']));
	foreach($data as $pred=>$oarray)
	{
		if(lcfirst($pred)=='hasModel') { continue; }
		foreach($oarray as $obj)
		{
			echo $this->element('relupdate',array('rel'=>array('pred'=>$pred,'obj'=>$obj),'prepend'=>'Relationship.'.$c.'.','preds'=>$preds,'objs'=>$objs));
			$c++;
		}
	}
	echo $this->Form->end('Update Relationships');
	?>
</div>