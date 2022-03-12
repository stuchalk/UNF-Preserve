<div class="left">
	<?php echo "<h2>".$data['objLabel']."<h2>"; ?>
</div>
<div class="right">
	<h3>Collections</h3>
	<ul>
	<?php foreach($data['cols'] as $pid=>$title) { echo '<li>'.$this->Html->link($title,'/collections/view/'.$pid).'</li>'; } ?>
	</ul>
</div>