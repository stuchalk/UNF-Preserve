<?php
// Element: Show search box
// File: gsearchform.ctp
// Variables: $vis
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: gsearchform.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('gsearch');">Search</div>
		<div class="clear"></div>
	</div>
	<div id="gsearch" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<?php
		echo $this->Form->create('Services',array('action'=>'gsearch'));
		echo $this->Form->input('field',array('type'=>'hidden','value'=>'any'));
		echo $this->Form->input('value',array('label'=>false,'div'=>false));
		echo $this->Form->end();
		?>
	</div>
</div>