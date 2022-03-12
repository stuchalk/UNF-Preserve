<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>Add a New Website</h2>
	<div style="width: 500px;">
	<?php
        $invs=[''=>'Select...','bird'=>'Birds','fish'=>'Fish','invert'=>'Invertebrates','lichen'=>'Lichens','mammal'=>'Mammals','plant'=>'Plants','herp'=>'Reptiles/Amphibians'];
		echo $this->Form->create('Website',['action'=>'add']);
		$fields=['Inventory'=>'type','Name'=>'name','Home Page'=>'homepage','Base URL'=>'url','Abbreviation'=>'ns'];
		foreach($fields as $key=>$field)
		{
			echo "<div style='display: table-row;'>";
			echo "<div style='width: 150px;text-align: right;display: table-cell;'>".$key."</div>";
			echo "<div style='width: 350px;display: table-cell;'>";
            if($field=="type") {
                echo $this->Form->input('type',['type'=>'select','options'=>$invs,'label'=>false,'div'=>false]);
            }
            else {
                echo $this->Form->input($field,['type'=>'text','size'=>'40','value'=>'','label'=>false,'div'=>false]);
            }
			echo "</div></div>";
		}
		echo "<div style='display: table-row;'>";
		echo "<div style='width: 150px;text-align: right;display: table-cell;'>".$this->Form->input('updated', ['type'=>'hidden','value'=>date(DATE_ATOM)])."</div>";
		echo "<div style='width: 350px;display: table-cell;text-align: right;'>".$this->Form->end('Add')."</div>";
		echo "</div>";
	?>
	</div>
</div>