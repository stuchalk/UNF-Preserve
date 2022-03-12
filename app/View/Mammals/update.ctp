<?php $mammal=$data['Mammal']; ?>
<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>Update Mammal Information</h2>
	<div style="width: 500px;">
	<?php
		echo $this->Form->create('Mammal',array('action'=>'update/'.$mammal['id']));
		$fields=array('Common Name'=>'cname','Scientific Name'=>'sname','Genus'=>'genus','Family'=>'family','Species'=>'species','URL'=>'url','Status'=>'status','Distribution'=>'distribution','Stock photo'=>'image_url','Comments'=>'comment');
		foreach($fields as $key=>$field)
		{
			echo "<div style='display: table-row'>";
			echo "<div style='width: 150px;text-align: right;display: table-cell'>".$key."</div>";
			echo "<div style='width: 350px;display: table-cell'>".$this->Form->input($field,array('type'=>'text','size'=>'40','value'=>$mammal[$field],'label'=>false,'div'=>false))."</div>";
			echo "</div>";
		}
		echo "<div style='display: table-row'>";
		echo "<div style='width: 150px;text-align: right;display: table-cell'>&nbsp;</div>";
		echo "<div style='width: 350px;display: table-cell'>".$this->Form->input('protected',array('type'=>'radio','options'=>array('0'=>'Open','1'=>'Protected'),'value'=>$mammal['protected'],'label'=>false,'div'=>false,'legend'=>false))."</div>";
		echo "</div>";
		echo "<div style='display: table-row'>";
		echo "<div style='width: 150px;text-align: right;display: table-cell'>&nbsp;</div>";
		echo "<div style='width: 350px;display: table-cell;text-align: right;'>".$this->Form->end('Update')."</div>";
		echo "</div>";
	?>
	</div>
</div>