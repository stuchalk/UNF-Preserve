<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2 style="float: left;">Invertebrate Inventory</h2>
	<?php echo $this->element('controls',array('table'=>'Invert'));?>
	<?php
		$temp=$data;
		$data=array();
		foreach($temp as $pid=>$title) { $data[$title[0]][$pid]=$title; }
		$charstr="";
		foreach($data as $letter=>$parray) { $charstr.='"'.$letter.'",'; }
		echo "<p>Browse alphabetically or download a ".$this->Html->link('PDF','/files/pdf/Insects and Invertebrates of the UNF Sawmill Slough Preserve.pdf')." of the inventory</p>";
		echo "<p style='text-align: center;'>";
		foreach($data as $char=>$parray) { echo $this->Html->link($char,'javascript:void(0)',array('div'=>false,'onclick'=>"togglevisset('".$char."',[".substr($charstr,0,-1)."]);"))." "; }
		$chars=array_keys($data);
		foreach($data as $char=>$parray)
		{
			if($char==$chars[0]):	echo "<div id='".$char."' style='display: block;'>";
			else:					echo "<div id='".$char."' style='display: none;'>";
			endif;
			echo "<ul>";
			foreach($parray as $pid=>$title)
			{
				echo '<li>'.html_entity_decode($this->Html->link($title,'/inverts/view/'.$pid)).'</li>';
			}
			echo "</ul></div>";
		}
	?>
</div>