<?php
// DIV for modal box
// Element Variables: $text, $namedate
// Random number is required so that dialogs are unique on the page
// NOTE: Every time you display a dialog it creates an additional new hidden dialog.
//       This is from the page that is loaded into the dialog window - ignore it...
// v1.0 SJC 9/2/13
if(!isset($link))		{ $link="No link text!"; }
if(!isset($image))		{ $image=false; }
if(!isset($url))		{ $url=Configure::read('jaf.path')."/pages/nourl"; }
if(!isset($height))		{ $height=500; }
if(!isset($width))		{ $width=800; }
if(!isset($type))		{ $type=""; }
if(!isset($title))		{ $title=$link; }
$rand=rand(1000,9999);
?>

<!-- element:right/modal.ctp-->
<?php
	if($image)
	{
		echo $this->Html->link($this->Html->image($link.".png",array('width'=>'16px')), $url, array('class'=>'modal'.$rand,'escape'=>false,'title'=>ucfirst($title)));
	}
	else
	{
		echo $this->Html->link($link, $url, array('class'=>'modal'.$rand,'title'=>$title));
	}
?>
<div id="modal<?php echo $rand; ?>"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#modal<?php echo $rand; ?>").dialog(
			{
				autoOpen: false,
				height: <?php echo $height; ?>,
				width: <?php echo $width; ?>,
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