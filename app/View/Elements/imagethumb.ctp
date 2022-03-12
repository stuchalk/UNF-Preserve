<?php
	// Display image thumbnail with modal box
	// Element variables: $type, $name, $pid, $thumbmax
	if(!isset($type))		{ $type="species"; }
	if(!isset($name))		{ $name=""; }
	if(!isset($thumbmax))	{ $thumbmax=300; }
?>

<?php
if(isset($url)) {
	// Display thumbnail
	echo "<img src='/".$url."' class='img-responsive center-block' style='max-height: ".$thumbmax."px;'/>";
} elseif(isset($pid)) {
	// Display thumbnail
	echo "<img src='/streams/thumb/".$sid."' class='img-responsive center-block'/>";
} else {
	echo "<p>No photo available</br>";
}
?>