<?php
// Element: Display content in iframe
// File: viewiframe.ctp
// Variables: $url, $width, $height
// v1.0 SJC 12/17/12
?>
<!-- element: viewiframe.ctp -->
<?php
if(!isset($width)) { $width="100%"; }
if(!isset($height)) { $height="100%"; }
?>
<iframe width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="<?php echo $url; ?>" frameborder="0"></iframe>