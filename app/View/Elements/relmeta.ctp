<?php
// Element: DIV to show metadata of the relationships (RELS-EXT) stream
// File: relmeta.ctp
// Variables: $args (from view), $vis
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
$meta=$this->requestAction('/datastreams/metadata/'.$args['pid'].'/RELS-EXT');  // $dsid not availble in view as specific datastream
?>
<!-- element: relmeta.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('relmeta');return false;">About</div>
		<div class="clear"></div>
	</div>
	<div id="relmeta" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
			<li>Title: <?php echo $meta['dsLabel']; ?></li>
			<li>Version: <?php echo $meta['dsVersionID']; ?></li>
			<li>Created: <?php echo date("m/d/y, g:i a",strtotime($meta['dsCreateDate'])); ?></li>
			<li>Media Type: <?php echo $meta['dsMIME']; ?></li>
			<li>Media Size: <?php echo number_format($meta['dsSize']/1024,1)." KB"; ?></li>
			<li>Stream Type: <?php echo $meta['dsControlGroup']; ?></li>
			<?php if(isset($meta['dsAltID']))	echo "<li>Uploaded Filename:<br />".str_replace("_"," ",$meta['dsAltID'])."</li>"; ?>
		</ul>
	</div>
</div>