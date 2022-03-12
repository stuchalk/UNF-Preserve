<?php
	// Used on images pages to show a form for upload of new photos to the UNF archive
	// Variables: $type, $id, $pcount
	$type=ucfirst($type); // Conforms to CakePHP data upload structure
?>

<!-- preserve/addphotoform -->
<div id="AddPhotos">
	<h3>Add Additional Photographs</h3>
	<?php echo $this->Form->create($type,['action'=>'addimage','enctype'=>'multipart/form-data']); ?>
	<?php echo $this->Form->input('col',['type'=>'hidden','value'=>$col]); ?>
	<?php echo $this->Form->input('id',['type'=>'hidden','value'=>$id]); ?>
	<?php echo $this->Form->input('pcount',['type'=>'hidden','value'=>$pcount]); ?>
	<table>
		<tr>
			<td class="textright">
                <p class="photolabel">Photo 1:</p>
                <p class="photolabel">Photo 2:</p>
                <p class="photolabel">Photo 3:</p>
                <p class="photolabel">Photo 4:</p>
                <p class="photolabel">Photo 5:</p>
			</td>
			<td>
				<?php echo $this->Form->input($type.'.upload.0',['type'=>'file','label'=>false,'div'=>false]); ?><br />
				<?php echo $this->Form->input($type.'.upload.1',['type'=>'file','label'=>false,'div'=>false]); ?><br />
                <?php echo $this->Form->input($type.'.upload.2',['type'=>'file','label'=>false,'div'=>false]); ?><br />
                <?php echo $this->Form->input($type.'.upload.3',['type'=>'file','label'=>false,'div'=>false]); ?><br />
				<?php echo $this->Form->input($type.'.upload.4',['type'=>'file','label'=>false,'div'=>false]); ?>
			</td>
			<td>
				<?php echo $this->Form->input($type.'.upload.0.creator',['type'=>'text','size'=>'40','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?><br />
				<?php echo $this->Form->input($type.'.upload.1.creator',['type'=>'text','size'=>'40','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?><br />
                <?php echo $this->Form->input($type.'.upload.2.creator',['type'=>'text','size'=>'40','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?><br />
                <?php echo $this->Form->input($type.'.upload.3.creator',['type'=>'text','size'=>'40','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?><br />
				<?php echo $this->Form->input($type.'.upload.4.creator',['type'=>'text','size'=>'40','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?>
			</td>
		</tr>
	</table>
	<p><?php echo $this->Form->end('Add Photos'); ?></p>
</div>