<div class="left">
	<h2>Results for "<?php echo $args['terms']; ?>"</h2>
</div>
<div class="right">
	<h3>Items Found in the Collection</h3>
	<ul>
	<?php foreach($data as $pid=>$title) { echo '<li>'.$this->Html->link($title,'/objects/view/'.$pid).'</li>'; } ?>
	</ul>
</div>