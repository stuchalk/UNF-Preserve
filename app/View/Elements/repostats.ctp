<?php
// Element: Shows repository statistics
// File: repostats.ctp
// Variables: None
// v1.0 SJC 11/24/12
$spaces=$this->requestAction('/repository/stats');
$stats=array();
foreach($spaces as $ns)
{
	$temp=$this->requestAction('/objects/listall/pid~'.$ns."*");
	$stats[$ns]=count($temp['results']);
}
?>
<!-- element: repostats.ctp -->
<h3>Statistics</h3>
<div class="righttextbox">
	<ul>
	<?php
	foreach($stats as $space=>$total)
	{
		echo "<li>There are ".$total." objects in the ".$this->Html->link($space,'/objects/fsearch/'.$space.":*")." namespace</li>";
	}
	?>
	</ul>
</div>