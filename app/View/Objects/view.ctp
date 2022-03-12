<div class="left">
	<h2>View Object</h2>
	<div class="leftspacer"></div>
	<?php echo $this->element('objmeta'); ?>
	<?php echo $this->element('objstrs'); ?>
	<?php echo $this->element('objhistory',array('vis'=>false)); ?>
	<?php echo $this->element('objadmin'); ?>
</div>
<div class="right">
	<?php pr($data); ?>
	<h2><?php echo $data['profile']['objLabel']; ?></h2>
	<p class="browse">This object has <?php echo count($data['streams']); ?> streams and the following relationships</p>
	<h3>Dublin Core</h3>
	<div class="righttextbox">
	<?php echo $this->element('viewiframe',array('url'=>Configure::read('jaf.path').'/services/saxon/'.$args['pid'].'*DC/test:xslt*XSLT')); ?>
	</div>
	<h3>Assertions or Relationships to Other Objects (RELS-EXT)</h3>
	<?php echo $this->element('relsext'); ?>
	<h3>Relationships to this Object from Other Objects</h3>
	<?php echo $this->element('relsin'); ?>
	<?php echo $this->element('viewvars'); ?>
</div>