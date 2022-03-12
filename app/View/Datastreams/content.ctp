<?php if($args['output']=="modal") { echo $this->element('viewmodal'); } else { ?>
<div class="left">
	<h2>Datastream Content</h2>
	<?php echo $this->element('objstrs'); ?>
</div>
<div class="right">
	<h3>Arguments</h3>
	<?php echo $this->element('viewdata',array('data'=>$args)); ?>
	<h3>Data</h3>
	<?php echo $this->element('viewdata',array('data'=>$data)); ?>
</div>
<?php } ?>