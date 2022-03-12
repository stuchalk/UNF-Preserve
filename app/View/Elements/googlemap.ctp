<?php
// Element: Google map viewer
// File: googlemap.ctp
// Variables: $lat, $lon, $zlevel, $type, $desc, $points, $width, $height
// v1.0 SJC 12/17/12
if(!isset($zlevel)) { $zlevel="15"; }
if(!isset($width)) { $width="700px"; }
if(!isset($height)) { $height="500px"; }
?>
<!-- element: googlemap.ctp -->
<img src="/img/empty.png" onload="gmap('<?php echo $lat; ?>','<?php echo $lon; ?>',<?php echo $zlevel; ?>,'<?php echo $type; ?>','<?php echo $desc; ?>','<?php echo $points; ?>'); return false;"/>
<div id="mapdiv" style="width: <?php echo $width; ?>;height: <?php echo $height; ?>;"></div>