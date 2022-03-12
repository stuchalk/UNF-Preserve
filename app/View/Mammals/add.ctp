<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>Add a New Mammal</h2>
	<div style="width: 500px;">
	<?php
		echo $this->Form->create('Mammal',['action'=>'add']);
		$fields=['Common Name'=>'cname','Scientific Name'=>'sname','Genus'=>'genus','Family'=>'family','Species'=>'species','URL'=>'url','Status'=>'status','Distribution'=>'distribution','Stock photo'=>'image_url','Comments'=>'comment'];
        foreach($fields as $key=>$field)
        {
            echo "<div style='display: table-row;'>";
            echo "<div style='width: 150px;text-align: right;display: table-cell;'>".$key."</div>";
            echo "<div style='width: 350px;display: table-cell;'>";
            if($field=="url") { echo $this->Form->input('ns',['type'=>'select','options'=>[''=>'Select website...']+$ns,'label'=>false,'div'=>false]); }
            echo $this->Form->input($field,['type'=>'text','size'=>'40','value'=>'','label'=>false,'div'=>false])."</div>";
            echo "</div>";
        }
        echo "<div style='display: table-row;'>";
        echo "<div style='width: 150px;text-align: right;display: table-cell;'>&nbsp;</div>";
        echo "<div style='width: 350px;display: table-cell;'>".$this->Form->input('protected',['type'=>'radio','options'=>['0'=>'Open','1'=>'Protected'],'value'=>0,'label'=>false,'div'=>false,'legend'=>false])."</div>";
        echo "</div>";
        echo "<div style='display: table-row;'>";
        echo "<div style='width: 150px;text-align: right;display: table-cell;'>".$this->Form->input('updated', ['type'=>'hidden','value'=>date(DATE_ATOM)])."</div>";
        echo "<div style='width: 350px;display: table-cell;text-align: right;'>".$this->Form->end('Add')."</div>";
        echo "</div>";
	?>
	</div>
</div>