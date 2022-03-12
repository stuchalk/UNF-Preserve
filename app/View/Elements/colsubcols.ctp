<?php
// Element: Display a list of subcollections of a collection
// File: colsubcols.ctp
// Variables: $args['pid'] (from view), $vis
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: colsubcols.ctp -->
<?php
$data=$this->requestAction('/collections/subcols/'.$args['pid']);
if(!empty($data['subcols'])) {
?>
	<div class="leftdiv">
		<div class="leftdivheader">
			<div class="leftdivtitle" onclick="togglevis('colsubcols');">Subcollections</div>
			<div class="clear"></div>
		</div>
		<div id="colsubcols" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
			<ul>
			<?php foreach($data['subcols'] as $pid=>$title) { echo "<li>".$this->Html->link($title,'/collections/view/'.$pid)."</li>"; } ?>
			</ul>
		</div>
	</div>
<?php } ?>