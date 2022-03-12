<?php
// Element: DIV to show metadata of an object
// File: strmeta.ctp
// Variables: $data, $vis
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
$meta=$this->requestAction('/datastreams/metadata/'.$args['pid'].'/'.$args['dsid']);
?>
<!-- element: strmeta.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('strmeta');return false;">About</div>
		<div class="clear"></div>
	</div>
	<div id="strmeta" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
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