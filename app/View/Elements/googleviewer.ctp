<?php
// Element: Display Google document viewer
// File: googleviewer.ctp
// Variables: $url, $height
// v1.0 SJC 1/14/12
?>
<!-- element: googleviewer.ctp -->
<?php if(!isset($height)) $height="900";?>
<iframe src="https://docs.google.com/viewer?url=<?php echo $url; ?>&embedded=true" width="100%" height="<?php echo $height; ?>" style="border: none;"></iframe>
<b><?php echo $url; ?></b>
