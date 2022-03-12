<?php
// Element: Show name(author) and date of object
// File: namedate.ctp
// Variables: $name, $datetime
// v1.0 SJC 12/17/12
?>
<!-- element: namedate.ctp -->
<div class="namedate">
	<?php
		echo $text." ";
		if($name!="") { echo $this->Html->link($name,'/users/view/'.$pid).", "; }
		echo date("m/d/y g:i a", strtotime($datetime));
	?>
</div>
<div class="clear"></div>