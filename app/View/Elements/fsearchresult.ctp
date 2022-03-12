<?php
// Element: Display findobject result
// File: fsearchresult.ctp
// Variables: $hit
// v1.0 SJC 12/17/12
?>
<!-- element: fsearchresult.ctp -->
<div class="righttextbox">
	<h3><?php echo $this->Html->link($hit['title'],'/'.lcfirst($this->name).'/view/'.$hit['pid']); ?></h3>
	<p class="browse">
	Author: <?php echo $hit['creator']."<br />"; ?>
	Added: <?php echo date("m/d/y",strtotime($hit['cDate'])); ?>
	</p>
</div>