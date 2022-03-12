<div class="left">
	<h2>All Items</h2>
	<?php echo $this->element('collist'); ?>
	<?php echo $this->element('objingest'); ?>
</div>
<div class="right">
	<h2>Items in Collection: <?php echo $args['col']; ?></h2>
	<ul>
	<?php foreach($data as $pid=>$title) { echo '<li>'.$this->Html->link($title,'/items/view/'.$pid).'</li>'; } ?>
	</ul>
</div>