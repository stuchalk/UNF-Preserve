<?php
// Controls div for access to the items in a table
// Element variables: $table
// SJC 11/25/14
$plural=Inflector::pluralize($table);
?>
<div id="controls" class="pull-right text-right">
	<?php
	echo "<b>Sort by</b> ";
	if(!isset($args['sort'])) {
	    echo $this->Html->link('Scientific','/'.$plural.'/index/sname')." | ".$this->Html->link('Common','/'.$plural.'/index/cname');
	} elseif($args['sort']=="sname") {
	    echo "Scientific | ".$this->Html->link('Common','/'.$plural.'/index/cname');
	} else {
	    echo $this->Html->link('Scientific','/'.$plural.'/index/sname')." | Common";
	}
	echo "<b> Name</b><br />";
	?>
</div>