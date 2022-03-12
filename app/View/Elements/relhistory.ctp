<?php
// Element: DIV to show metadata of an object
// File: strhistory.ctp
// Variables: $data, $vis
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
$history=$data['history'];
//$history=$this->requestAction('/datastreams/history/'.$args['pid'].'/RELS-EXT');
?>
<!-- element: strhistory.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('strhistory');return false;">Versions</div>
		<div class="clear"></div>
	</div>
	<div id="strhistory" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
			<?php
			foreach($history as $version=>$date)
			{
				echo "<li>".$this->Html->link(date("m/d/y, g:i:s a",strtotime($date)),'/datastreams/view/'.$args['pid'].'/RELS-EXT'.'?asOfDateTime='.$date)."</li>";
			}
			?>
		</ul>
	</div>
</div>