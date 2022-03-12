<div class="left">
	<h2>Update Object</h2>
	<div class="leftspacer"></div>
	<?php echo $this->element('objadmin'); ?>
</div>
<div class="right">
	<h2>Edit</h2>
	<h3>Object Metadata</h3>
	<div class="righttextbox">
		<?php echo $this->Form->create('Object',array('action'=>'/update/'.$args['pid'])); ?>
		<table width="100%">
			<tr>
				<td class="textright">Label: </td>
				<td><?php echo $this->Form->input('Object.label',array('label'=>false,'size'=>50,'value'=>$data["objLabel"])); ?></td>
			</tr>
			<tr>
				<td class="textright">OwnerId: </td>
				<td><?php echo $this->Form->input('Object.ownerId',array('label'=>false,'size'=>40,'value'=>$data["objOwnerId"])); ?></td>
			</tr>
			<tr>
				<td class="textright">State: </td>
				<td><?php echo $this->Form->input('Object.state',array('type'=>'select','label'=>false,'options'=>array('A'=>'A','I'=>'I','D'=>'D'),'selected'=>'<?php echo $data["objState"] ?>')); ?></td>
			</tr>
			<tr>
				<td class="textright">Message: </td>
				<td><?php echo $this->Form->input('Object.logMessage',array('label'=>false,'size'=>60)); ?></td>
			</tr>
		</table>
		<?php echo $this->Form->end('Update Object Metadata'); ?>
	</div>
	<h3>Dublin Core Metadata</h3>
	<?php echo $this->element('strupdate'); ?>
</div>