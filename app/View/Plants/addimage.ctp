<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>Add Photos to the Library</h2>
	<?php echo $this->Form->create('Plant',array('action'=>'addimage','enctype'=>'multipart/form-data')); ?>
	<table width="700">
		<tr>
			<?php echo $this->Form->input('col',array('type'=>'hidden','value'=>$args['col'])); ?>
			<td width="100" class="textright">Plant:</td>
			<td width="600" >
			<?php echo $this->Form->input('id',array('type'=>'select','label'=>false,'selected'=>'empty','options'=>array(''=>'Choose...'),'onfocus'=>'getSelectOptions("/plants/index","PlantId");return false;','div'=>false,'align'=>'top')); ?><br>
		</tr>
		<tr>
			<td class="textright" style="align: top;">File(s):</td>
			<td>
				<?php echo $this->Form->input('Plant.upload.0',array('type'=>'file','label'=>false,'div'=>false)); ?><br />
				<?php echo $this->Form->input('Plant.upload.1',array('type'=>'file','label'=>false,'div'=>false)); ?><br />
				<?php echo $this->Form->input('Plant.upload.2',array('type'=>'file','label'=>false,'div'=>false)); ?>
			</td>
		</tr>
	</table>
	<p><?php echo $this->Form->end('Add Photos'); ?></p>
</div>