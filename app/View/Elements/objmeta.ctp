<?php
// Element: DIV to show thumbnail of an object
// File: objmeta.ctp
// Variables: $args['pid'] (from view), $vis (optional)
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
$meta=$this->requestAction('/objects/profile/'.$args['pid']);
?>
<!-- element: objmeta.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('objmeta');">About</div>
		<div class="clear"></div>
	</div>
	<div id="objmeta" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
		<?php
			echo "<li>Owner: ".$meta['objOwnerId']."</li>";
			echo "<li>Added: ".date("m/d/Y",strtotime($meta['objCreateDate']))."</li>";
			echo "<li>Updated: ".date("m/d/Y",strtotime($meta['objLastModDate']))."</li>";
		?>
		</ul>
	</div>
</div>