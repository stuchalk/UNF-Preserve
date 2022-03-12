<?php
// Element: Weather Underground weather viewer
// File: weather.ctp
// Variables: $lat, $lon, $date
// Weather Underground Key for ecenter.unf.edu: 6ccbe854109596eb
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
// Location
$temp=$this->requestAction('/datastreams/content/'.$args['pid'].'/KML');
$kml=$temp['content']['Kml'];
$gps=$kml['Placemark']['Point']['coordinates'];
list($lon,$lat,$alt)=explode(",",$gps,3);
// Datetime
$temp=$this->requestAction('/datastreams/content/'.$args['pid'].'/EXIF');
$datetime=$temp['content']['Exif']['Date_and_Time'];
// Get local stations
$json=file_get_contents('http://api.wunderground.com/api/6ccbe854109596eb/geolookup/q/'.$lat.','.$lon.'.json');
$stations=json_decode($json,true);
// Nearest station is the first in the list of public weather stations (pws)
$station=$stations['location']['nearby_weather_stations']['pws']['station'][0];
// Get the weather history for the data of the image
$date=date('Ymd',strtotime($datetime));
$hour=date('H',strtotime($datetime));
$min=date('i',strtotime($datetime));
//
// Override station here for UNF location as the closest is UNF/San Pablo (KFLJACK18) - Remove so it works for any location
$station=array('id'=>'KFLJACKS18','neighborhood'=>'UNF/San Pablo');
//
$json=file_get_contents('http://api.wunderground.com/api/6ccbe854109596eb/history_'.$date.'/q/pws:'.$station['id'].'.json');
$data=json_decode($json,true);
// Pull out the conditions when this was taken
$readings=$closeness=array();
foreach($data['history']['observations'] as $obs)
{
	if($obs['date']['hour']==$hour)
	{
		$readings[]=$obs;
	}
}
foreach($readings as $index=>$reading)
{
	$diff=abs($min-$reading['date']['min']);
	$closeness[$diff]=$index;
}
ksort($closeness);
$index=current($closeness);
$weather=$readings[$index];
?>
<!-- element: weather.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('weather');">Weather</div>
		<div class="clear"></div>
	</div>
	<div id="weather" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
		<?php
		// Finally, print out the weather!
		echo "<h4>Station - ".$station['neighborhood']."<br />".$weather['date']['pretty']."</h4>";
		echo "<ul>";
		echo "<li>Temperature: ".$weather['tempi']."°C</li>";
		echo "<li>Humidity: ".$weather['hum']."%</li>";
		echo "<li>Pressure: ".$weather['pressurei']." in</li>";
		echo "<li>Wind Speed: ".$weather['wspdi']." from ".$weather['wdire']."</li>";
		echo "<li>Precipitation: ".$weather['precip_totali']." in</li>";
		echo "</ul>";
		echo "<p><i>Data from ".$html->link('Weather Underground','http://www.wundergound.com/?apiref=6ccbe854109596eb',array('target'=>'_blank'))."</i></p>"
		?>
		</ul>
	</div>
</div>