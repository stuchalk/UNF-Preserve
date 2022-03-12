<div class="left">
	<h2>Add a collection</h2>
</div>
<div class="right">
	<?php echo $this->Form->create('Collection',array('action'=>'add','enctype'=>'multipart/form-data')); ?>
	<table width="680">
		<tr>
			<td width="80" class="textright">&nbsp;</td>
			<td width="600"><?php echo $this->Form->input('parentcol',array('type'=>'select','options'=>array(''=>'Select Master Collection...')+$data,'label'=>false,'div'=>false)); ?></td>
		</tr>
		<tr>
			<td class="textright">*Title:</td>
			<td><?php echo $this->Form->input('Collection.dc.title',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50')); ?></td>
		</tr>
		<tr>
			<td class="textright">Author:</td>
			<td><?php echo $this->Form->input('Collection.dc.creator',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50')); ?></td>
		</tr>
		<tr>
			<td class="textright">Keywords:</td>
			<td><?php echo $this->Form->input('Collection.dc.subject',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50')); ?></td>
		</tr>
		<tr>
			<td class="textright">Description:</td>
			<td><?php echo $this->Form->input('Collection.dc.description',array('type'=>'textarea','label'=>false,'div'=>false,'rows'=>'2','cols'=>'50')); ?></td>
		</tr>
		<tr>
			<td class="textright">Image:</td>
			<td><?php echo $this->element('strForm',array('prepend'=>'Collection.streams.1.')); ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="textright"><?php echo $this->Form->end('Add Collection'); ?></td>
		</tr>
	</table>
</div>