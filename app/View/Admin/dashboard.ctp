<?php
	// Data setup - $tables
	$jarray="['".implode("','",$tables)."']";
	$t="Admin";
?>
<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>CNAI Administration</h2>
	<p>Click a header to show options for that type...</p>
	<?php
		$show="block";
		foreach($tables as $table) {
			$name=ucfirst(Inflector::singularize($table));
			$results=$this->requestAction('/'.$table);
			$options=[''=>'Choose...']+$results;
			echo '<h3 onclick="togglevisset(\''.$table.'\','.$jarray.');" class="click">'.$name." Inventory</h3>";
			echo '<div id="'.$table.'" class="images" style="display: '.$show.'">';
			?>
			<ul>
				<li><div style="line-height; 25px;"><?php echo $this->Html->link('Add '.$name,'/'.$table.'/add'); ?></div></li>
				<li><?php echo $this->Form->input('Update info',['options'=>$options,'onchange'=>"document.location.href='/".$table."/update/'+this.options[this.selectedIndex].value"]); ?></li>
				<li><?php echo $this->Form->input('Update images',['options'=>$options,'onchange'=>"document.location.href='/".$table."/images/'+this.options[this.selectedIndex].value"]); ?></li>
			    <li><?php echo $this->Form->input('Delete '.$name,['options'=>$options,'onchange'=>"confirmDelete('/".$table."/delete/'+this.options[this.selectedIndex].value,'select');return false;"]); ?></li>
            </ul>
			<?php
			echo "</div>";
			if($show=="block") { $show="none"; }
		}
	?>
	<hr style="margin: 10px 0px 10px 0px;" />
	<h4>Other options...</h4>
	<div id="webpages">
		<div style="width: 150px;float: left;">
			<h3>Update Web Pages</h3>
		</div>
		<div style="float: left;">
			<?php $pages=$this->requestAction('/webpages'); $options=[''=>'Choose page...']+$pages; ?>
			<?php echo $this->Form->input('',['options'=>$options,'onchange'=>"document.location.href='/webpages/edit/'+this.options[this.selectedIndex].value+'/dash'"]); ?>
		</div>
		<div style="float: right;width: 150px;">
			<?php echo $this->Session->flash(); ?>
		</div>
		<div class='clear'></div>
	</div>
	<div id="pdfs">
		<div style="width: 150px;float: left;">
			<h3>Update Inventory PDF</h3>
		</div>
		<div style="float: left;">
			<?php
				echo $this->Form->create('Admin',['action'=>'addpdf','enctype'=>'multipart/form-data']);
				$options=[];foreach($tables as $table) { $options[$table]=ucfirst($table)." Inventory PDF"; }
				echo $this->Form->input('pdftype',['type'=>'select','label'=>false,'div'=>false,'options'=>$options,'empty'=>'Select inventory...']);
				echo $this->Form->input('upload',['type'=>'file','label'=>false,'div'=>false]);
				echo $this->Form->end(['label'=>'Add PDF','div'=>false]);
			?>
		</div>
		<div class='clear'></div>
	</div>
	<div id="webimages">
		<h3 style="cursor: pointer;" onclick="document.location.href='/photos';">Add/Update Webpage Photos</h3>
	</div>
    <div id="websites">
        <h3 style="cursor: pointer;" onclick="document.location.href='/websites/add';">Add New Inventory Website</h3>
    </div>
</div>