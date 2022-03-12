<?php
if(!isset($type))		{ $type="species"; }
if(!isset($name))		{ $name=""; }
if(!isset($ori))		{ $ori="land"; }
if(!isset($width))		{ $width="220"; }
$rand=rand(1000,9999);

if(isset($pid))
{
	// Get size of image to set height and width of modal box
	$source="https://preserve.unf.edu/datastreams/content/".$pid."/Source/download";
	$image=getimagesize($source); // $image[0] is width, $image[1] is height
	$max=800;
	pr($image);
	if($image[0]>$image[1]):	$w=$max;$h=$image[1]*($max/$image[0]);
	else:						$h=$max;$w=$image[0]*($max/$image[1]);
	endif;
		
	// Display image and modal link
	$url="https://preserve.unf.edu/utils/display/".$pid."/Source/".$w;
	echo '<a href="'.$url.'" title="Click to enlarge" class="modal'.$rand.'" escape="false">';
	echo $this->Html->image("https://preserve.unf.edu/utils/display/".$pid."/THUMB/500/download",array('style'=>'max-width: '.$width.'px;max-height: 220px;float: left;','class'=>'shadow'));
	echo "</a>";
}
else
{
	echo "<p>No photo available</br>";
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#modal<?php echo $rand; ?>").dialog(
			{
				autoOpen: false,
				height: <?php echo $h+40; ?>,
				width: <?php echo $w+40; ?>,
				resizable: true,
				modal: true,
				close: function(event,ui) { $(this).dialog('close'); }
		});
	});

	$(".modal<?php echo $rand; ?>").click(function(){
		$("#modal<?php echo $rand; ?>").load($(this).attr('href'), function ()
			{
				$("#modal<?php echo $rand; ?>").dialog('open');
				$('#<?php echo $type; ?>menus').remove();
			});
		return false;
	});
</script>