<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>Add Photos to the Library</h2>
	<?php echo $this->Form->create('Bird',['action'=>'addimage','enctype'=>'multipart/form-data']); ?>
	<table width="700">
		<tr>
			<?php echo $this->Form->input('col',['type'=>'hidden','value'=>$args['col']]); ?>
			<td width="100" class="textright">Bird:</td>
			<td width="600" >
			<?php echo $this->Form->input('id',['type'=>'select','label'=>false,'selected'=>'empty','options'=>[''=>'Choose...'],'onfocus'=>'getSelectOptions("/birds/index","BirdId");return false;','div'=>false,'align'=>'top']); ?><br>
		</tr>
		<tr>
			<td class="textright">File(s):</td>
			<td><?php echo $this->Form->input('Bird.upload.0',['type'=>'file','label'=>false,'div'=>false]); ?><br />
			<?php echo $this->Form->input('Bird.upload.1',['type'=>'file','label'=>false,'div'=>false]); ?><br />
			<?php echo $this->Form->input('Bird.upload.2',['type'=>'file','label'=>false,'div'=>false]); ?></td>
		</tr>
	</table>
	<p><?php echo $this->Form->end('Add Photos'); ?></p>
</div>