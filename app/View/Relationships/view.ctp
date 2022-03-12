<div class="left">
	<h2>View Relationships</h2>
	<?php echo $this->element('relmeta'); ?>
	<?php echo $this->element('relhistory'); ?>
	<?php echo $this->element('reladmin'); ?>
</div>
<div class="right">
	<?php pr($data); ?>
	<h2>Relationships for <?php echo $args['pid']; ?></h2>
	<?php echo $this->element('relsext'); ?>
</div>