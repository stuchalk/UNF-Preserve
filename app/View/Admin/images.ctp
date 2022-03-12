<?php
    // Data setup - $images coming from controller
	$jarray="['".implode("','",$types)."']";
	$t="Admin";
?>
<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>Available Webpage Photos</h2>
	<p>Click the header to show photos of that type</p>
	<?php
	$show="block";
	foreach($allimages as $type=>$images)
	{
		echo '<h3 onclick="togglevisset(\''.$type.'\','.$jarray.');" style="cursor: pointer;">'.ucfirst($type)." Photos</h3>";
		if(!empty($images))
		{
			echo '<div id="'.$type.'" class="images" style="display: '.$show.'">';
			foreach($images as $image)
			{
				echo '<div style="width: 225px;float: left;">';
				if(isset($labels[$image['pid']])) { echo '<p style="text-align: center;">/photos/show/'.$labels[$image['pid']].'</p>'; }
				echo $this->element('preserve/unfphoto',['divid'=>'photo'.$image['pid'],'type'=>$type,'name'=>$type,'photo'=>$image]);
				echo '</div>';
			}
			echo "<div class='clear'></div></div>";
			if($show=="block") { $show="none"; }
		}
	}
	?>
	<p>&nbsp;</p>
	<div id="AddPhotos">
		<h3>Add Additional Photographs</h3>
		<?php echo $this->Form->create($t,['action'=>'addimage','enctype'=>'multipart/form-data']); ?>
		<?php echo $this->Form->input('col',['type'=>'hidden','value'=>'unfenvc:30']); // 30 is the preserve collection ?>
		<?php foreach($types as $type) { echo $this->Form->input($t.'.count.'.$type,['type'=>'hidden','value'=>count($allimages[$type])]); } ?>
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
					<?php echo $this->Form->input($t.'.upload.0',['type'=>'file','label'=>false,'div'=>false]); ?><br />
					<?php echo $this->Form->input($t.'.upload.1',['type'=>'file','label'=>false,'div'=>false]); ?><br />
					<?php echo $this->Form->input($t.'.upload.2',['type'=>'file','label'=>false,'div'=>false]); ?><br />
					<?php echo $this->Form->input($t.'.upload.3',['type'=>'file','label'=>false,'div'=>false]); ?><br />
					<?php echo $this->Form->input($t.'.upload.4',['type'=>'file','label'=>false,'div'=>false]); ?>
				</td>
				<td>
					<?php $options=[];foreach($types as $type) { $options[$type]=ucfirst($type)." Photo"; } ?>
					<?php echo $this->Form->input($t.'.upload.0.itype',['type'=>'select','label'=>false,'div'=>false,'options'=>$options,'empty'=>'Select type...']); ?><br />
					<?php echo $this->Form->input($t.'.upload.1.itype',['type'=>'select','label'=>false,'div'=>false,'options'=>$options,'empty'=>'Select type...']); ?><br />
					<?php echo $this->Form->input($t.'.upload.2.itype',['type'=>'select','label'=>false,'div'=>false,'options'=>$options,'empty'=>'Select type...']); ?><br />
					<?php echo $this->Form->input($t.'.upload.3.itype',['type'=>'select','label'=>false,'div'=>false,'options'=>$options,'empty'=>'Select type...']); ?><br />
					<?php echo $this->Form->input($t.'.upload.4.itype',['type'=>'select','label'=>false,'div'=>false,'options'=>$options,'empty'=>'Select type...']); ?>
				</td>
				<td>
					<?php echo $this->Form->input($t.'.upload.0.creator',['type'=>'text','size'=>'25','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?><br />
					<?php echo $this->Form->input($t.'.upload.1.creator',['type'=>'text','size'=>'25','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?><br />
					<?php echo $this->Form->input($t.'.upload.2.creator',['type'=>'text','size'=>'25','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?><br />
					<?php echo $this->Form->input($t.'.upload.3.creator',['type'=>'text','size'=>'25','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?><br />
					<?php echo $this->Form->input($t.'.upload.4.creator',['type'=>'text','size'=>'25','label'=>false,'div'=>false,'placeholder'=>'Photographer']); ?>
				</td>
			</tr>
		</table>
		<p><?php echo $this->Form->end('Add Photos'); ?></p>
	</div>
</div>