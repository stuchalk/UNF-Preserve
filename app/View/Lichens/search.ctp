<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2 style="float: left;">Search Results (Plants)</h2>
	<?php echo $this->element('controls',array('table'=>'Plant'));?>
	<?php
		//pr($results);
		echo "<ul>";
		foreach($results as $pid=>$title)
		{
			echo '<li>'.html_entity_decode($this->Html->link($title,'/plants/view/'.$pid)).'</li>';
		}
		echo "</ul>";
	?>
</div>