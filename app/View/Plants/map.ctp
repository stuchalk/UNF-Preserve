<?php //pr($kml);exit; ?>
<?php if(!isset($kml)) { $kml=""; } ?>
<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>Plant Map</h2>
	<?php echo $this->element('googlemap',array('lat'=>'30.268','lon'=>'-81.508','type'=>'kml','desc'=>'University of North Florida','points'=>$kml['exturl'].'?dummy='.time())); ?>
</div>
