<?php if($args['output']=="modal") { echo $this->element('viewmodal'); } else { ?>
<div class="left">
	<h2>View Datastream</h2>
	<div class="leftspacer"></div>
	<?php echo $this->element('strmeta'); ?>
	<?php echo $this->element('strhistory'); ?>
	<?php echo $this->element('stradmin'); ?>
</div>
<div class="right">
	<h2>Content</h2>
	<?php echo $this->element('viewcontent'); ?>
	<?php echo $this->element('viewvars'); ?>
</div>
<?php } ?>