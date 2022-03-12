<?php
// Element: DIV to show thumbnail of an object
// File: objthumb.ctp
// Variables: $args['pid'] and $data (required from view), $width (optional), $float (optional)
// v1.0 SJC 12/17/12
?>
<!-- element: objthumb.ctp -->
<?php
// Defaults
$path=Configure::read('jaf.path');
$cover="no";$title="no";
$thumburl="";
if(!isset($width)) { $width="230"; }
// Figure out if there is an image and then the URL
if(isset($data['THUMB']))
{
	// Display the THUMB DS image
	$thumburl=$data['THUMB']['exturl'];
	if(isset($data['IMAGE'])) { $cover="yes"; }
}
elseif(isset($data['methods']['admin:im_sdef']))
{
	// Create THUMB on the fly by using ImageManip to generate resized image
	$thumburl=$path.'/methods/view/'.$pid.'/admin:im_sdef/resize?width='.$width;
}
elseif(isset($data['IMAGE']))
{
	// Use the IMAGE stream (bigger file size :( )
	if(isset($data['IMAGE']['icon'])):	$thumburl='/img/'.$data['IMAGE']['icon'];
	else:								$thumburl=$data['IMAGE']['exturl'];$cover="yes";
	endif;
}
// Display
?>
<div class="leftdiv" <?php if(isset($float)) { echo 'style="float: '.$float.';padding-left: 20px;"'; } ?>>
	<?php if($cover=="yes") { ?>
	<a href="javascript:void(0)" title="Click to enlarge" onclick="Modalbox.show('<?php echo $path; ?>/utils/display/<?php echo $args["pid"]; ?>/IMAGE/680', { title: 'Cover page', width: 710 }); return false;">
		<?php echo $this->Html->image($thumburl,array('width'=>$width,'class'=>'shadow')); ?>
	</a>
	<?php
	}
	else
	{
		if($thumburl!="") { echo $this->Html->image($thumburl,array('width'=>$width,'class'=>'shadow')); }
	}
	?>
</div>