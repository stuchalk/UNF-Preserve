<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2 style="float: left;">Search Results (Invertebrates)</h2>
	<?php echo $this->element('controls',array('table'=>'Invert'));?>
	<?php
		echo "<ul>";
		foreach($results as $pid=>$title)
		{
			echo '<li>'.html_entity_decode($this->Html->link($title,'/inverts/view/'.$pid)).'</li>';
		}
		echo "</ul>";
	?>
</div>