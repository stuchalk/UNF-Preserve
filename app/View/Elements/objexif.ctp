<?php
// Element: DIV to show EXIF data for an image file
// File: objexif.ctp
// Variables: $args['pid'] (from view), $vis (optional)
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
$temp=$this->requestAction('/datastreams/content/'.$args['pid'].'/EXIF');
$exif=$temp['content']['Exif']; ?>
<!-- element: objexif.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('objexif');">EXIF SUMMARY</div>
		<div class="clear"></div>
	</div>
	<div id="objexif" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
		<?php
			echo "<li>Camera: ".$exif['Model']."</li>";
			echo "<li>Taken: ".date("m/d/Y, g:i a",strtotime($exif['Date_and_Time']))."</li>";
			echo "<li>Resolution: ".$exif['Pixel_X_Dimension']." x ".$exif['Pixel_Y_Dimension']."</li>";
			if(isset($exif['Longitude'])&&isset($exif['Latitude']))
			{
				echo "<li>Latitude: ".$exif['North_or_South_Latitude']." ".$exif['Latitude']."</li>";
				echo "<li>Longitude: ".$exif['East_or_West_Longitude']." ".$exif['Longitude']."</li>";
			}
			else
			{
				echo "<li>Location: NA</li>";
			}
		?>
		</ul>
		<a href="javascript:void(0)" title="View all EXIF data"
			onclick="Modalbox.show('<?php echo Configure::read('jaf.path'); ?>/datastreams/view/<?php echo $data['pid']; ?>/EXIF/modal', { title: 'EXIF Data', width: 800 }); return false;">
			View all EXIF data
		</a>
	</div>
</div>