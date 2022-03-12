<?php
// Element: Display RI search results
// File: rsearchresult.ctp
// Variables: $hit
// v1.0 SJC 12/17/12
?>
<!-- element: rsearchresult.ctp -->
<div class="righttextbox">
	<h3><?php echo $this->Html->link($hit['title'],'/'.lcfirst($this->name).'/view/'.$hit['pid']); ?></h3>
	<p class="browse"><?php echo ucfirst($args['field']).": ".$hit[$args['field']]; ?></p>
</div>