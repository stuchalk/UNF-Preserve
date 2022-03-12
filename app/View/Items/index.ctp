<div class="left">
	<h2>Browse Items</h2>
	<div class="leftspacer"></div>
	<?php echo $this->element('collist'); ?>
	<?php echo $this->element('objingest'); ?>
</div>
<div class="right">
	<h2>Items Currently in the Library</h2>
	<?php
	if(count($data)>30)
	{
		$chars="";
		foreach($data as $char=>$iarray) { $chars.='"'.$char.'",'; }
		echo "<p>Click on a letter below to show titles starting with that letter</p>";
		echo "<p style='text-align: center;'>";
		foreach($data as $char=>$iarray) { echo $this->Html->link($char,'javascript:void(0)',array('div'=>false,'onclick'=>"togglevisset('".$char."',[".substr($chars,0,-1)."]);"))." "; }
		echo "</p>";
		$chars=array_keys($data);
		foreach($data as $char=>$iarray)
		{
			if($char==$chars[0]):	echo "<div id='".$char."' style='display: block;'>";
			else:					echo "<div id='".$char."' style='display: none;'>";
			endif;
			echo "<ul>";
			foreach($iarray as $pid=>$title)
			{
				echo '<li>'.html_entity_decode($this->Html->link($title,'/items/view/'.$pid)).'</li>';
			}
			echo "</ul></div>";
		}
	}
	else
	{
		echo "<ul>";
		foreach($data as $char)
		{
			foreach($char as $pid=>$title)
			{
				echo '<li>'.html_entity_decode($this->Html->link($title,'/items/view/'.$pid)).'</li>';
			}
		}
		echo "</ul>";
	}
	?>
</div>