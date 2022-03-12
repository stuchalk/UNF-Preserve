<?php
// Element: Display a video from a remote site
// File: viewvideo.ctp
// Variables: $type, $url
// Helpers: youtube
// v1.0 SJC 12/17/12
?>
<!-- element: viewvideo.ctp -->
<?php
if($type=="youtube"):	echo "<div class='youtube'>".$youtube->video($url)."</div>";
endif;
?> 