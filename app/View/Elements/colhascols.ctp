<?php
// Element: Show a list of the collections an object is in
// File: colhascols.ctp
// Variables: $args['pid'] (from view)
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: colhascols.ctp -->
<?php
$cols=$this->requestAction('/collections/hascols/'.$args['pid']);
if(!empty($cols)) {
?>
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('colhascols');">Collections</div>
		<div class="clear"></div>
	</div>
	<div id="colhascols" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
		<?php foreach($cols as $pid=>$title) { echo "<li>".$this->Html->link($title,'/collections/view/'.$pid)."</li>"; } ?>
		</ul>
	</div>
</div>
<?php } ?>