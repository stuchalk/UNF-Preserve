<div class="left">
	<h2>Update Collection</h2>
</div>
<div class="right">
	<div id="rightinner">
		<?php $dc=$data['DC']['content']['Dc']; ?>
		<h3>Collection:&nbsp;<?php echo $dc['title']; ?></h3>
		<?php echo $this->Form->create('Collection',array('action'=>'update/'.$dc['identifier'])); ?>
		<table width="700">
			<tr>
				<td class="textright">*Title:</td>
				<td><?php echo $this->Form->input('Collection.dc.title',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50','value'=>$dc['title'])); ?></td>
			</tr>
			<tr>
				<td class="textright">Author:</td>
				<td><?php echo $this->Form->input('Collection.dc.creator',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50','value'=>$dc['creator'])); ?></td>
			</tr>
			<tr>
				<td class="textright">Keywords:</td>
				<td><?php echo $this->Form->input('Collection.dc.subject',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50','value'=>$dc['subject'])); ?></td>
			</tr>
			<tr>
				<td class="textright">Description:</td>
				<td><?php echo $this->Form->input('Collection.dc.description',array('type'=>'textarea','label'=>false,'div'=>false,'rows'=>'3','cols'=>'45','value'=>$dc['description'])); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="textright"><?php echo $this->Form->end('Update Collection'); ?></td>
			</tr>
		</table>
	</div>
</div>