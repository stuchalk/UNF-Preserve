<?php
// Element: Show a list of the collections an object is in
// File: objcols.ctp
// Variables: $args['pid'] (from view)
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: objcols.ctp -->
<?php
$cols=$this->requestAction('/items/hascols/'.$args['pid']);
if(!empty($cols)) {
?>
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('objcols');">Collections</div>
		<div class="clear"></div>
	</div>
	<div id="objcols" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
		<?php foreach($cols as $pid=>$title) { echo "<li>".$this->Html->link($title,'/collections/view/'.$pid)."</li>"; } ?>
		</ul>
	</div>
</div>
<?php } ?>