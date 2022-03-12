<div class="left">
	<h2>Add a Datastream</h2>
	<?php echo $this->element('objadmin'); ?>
</div>
<div class="right">
	<h2><?php echo $data['objLabel']; ?></h2>
	<?php
	echo $this->Form->create('Datastream',array('enctype' => 'multipart/form-data','action'=>'add/'.$data['pid']));
	echo $this->element('strform',array('prepend'=>'Datastream.'));
	echo $this->Form->end('Add datastream');
	?>
</div>