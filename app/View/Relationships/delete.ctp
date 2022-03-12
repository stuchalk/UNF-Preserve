<div id="deleterelationship" class="righttextbox">
	<h3>Confirm Deletion</h3>
	<p>Are you sure you want to delete the relationship <?php echo $data['predicate'].'->'.$data['object']; ?> on <?php echo $pid; ?>?</p>
	<?php echo $this->Form->create('Relationship',array('url'=>'/relationships/delete/'.$pid)); ?>
	<?php echo $this->Form->input('predicate',array('type'=>'hidden','value'=>$data['predicate'])); ?>
	<?php echo $this->Form->input('object',array('type'=>'hidden','value'=>$data['object'])); ?>
	<?php echo $this->Form->input('isLiteral',array('type'=>'hidden','value'=>$data['isLiteral'])); ?>
	<?php echo $this->Form->input('delete',array('type'=>'hidden','value'=>'yes')); ?>
	<?php echo $this->Form->end('Delete'); ?>
</div>