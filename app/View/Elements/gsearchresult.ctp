<?php
// Element: Display GSearch result
// File: gsearchresult.ctp
// Variables: $hit
// v1.0 SJC 12/17/12
?>
<!-- element: gsearchresult.ctp -->
<div class="righttextbox">
	<h3><?php echo $this->Html->link($hit['title'],'/items/view/'.$hit['pid']); ?></h3>
	<?php if($hit['snippet']!="") { ?>
	<div style="margin-top: 6px;margin-bottom: 6px;">... <?php echo $hit['snippet']; ?> ...</div>
	<div class="clear"></div>
	<?php } ?>
</div>