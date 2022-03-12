<?php
// Element: Show the history of an object
// File: objhistory.ctp
// Variables: $data, $vis
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
$history=$this->requestAction('/objects/history/'.$args['pid']);
?>
<!-- element: objhistory.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('objhistory');return false;">Versions</div>
		<div class="clear"></div>
	</div>
	<div id="objhistory" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
			<?php
			foreach($history as $version=>$date)
			{
				echo "<li>".$this->Html->link(date("m/d/y, g:i:s a",strtotime($date)),'/objects/view/'.$args['pid'].'?asOfDateTime='.$date)."</li>";
			}
			?>
		</ul>
	</div>
</div>