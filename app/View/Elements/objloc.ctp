<?php
// Element: DIV to show the GPS location of an image file
// File: objloc.ctp
// Variables: $args['pid'] (from view), $vis (optional)
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
$temp=$this->requestAction('/datastreams/content/'.$args['pid'].'/KML');
$kml=$temp['content']['Kml']; ?>
<!-- element: objloc.ctp -->
<div class="leftdiv">
	<?php
	$gps=$kml['Placemark']['Point']['coordinates'];
	list($lon,$lat,$alt)=explode(",",$gps,3);
	echo $this->element('googlemap',array('lat'=>$lat,'lon'=>$lon,'zlevel'=>'18','type'=>'point','desc'=>'Description','points'=>'','width'=>'230px','height'=>'160px')); ?>
</div>