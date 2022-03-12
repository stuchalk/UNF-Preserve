<?php
// Element: Show the list of collections
// File: colstats.ctp
// Variables: $args['pid'] (from view), $vis
// v1.0 SJC 11/24/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: colstats.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('colstats');">Statistics</div>
		<div class="clear"></div>
	</div>
	<div id="colstats" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<?php
			$stats=$this->requestAction('/collections/stats/'.$args['pid']);
			echo "<ul>";
			echo "<li>Collection Items: ".$stats['stats']['items']."</li>";
			echo "<li>Sub-Collections: ".$stats['stats']['subcols']."</li>";
			echo "</ul>";
		?>
	</div>
</div>