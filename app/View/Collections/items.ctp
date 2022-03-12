<div class="left">
	<?php
		$streams=$data['streams'];
		if(isset($streams['THUMB'])&&isset($streams['IMAGE'])) { echo $this->element('objthumb',array('thumb'=>$args['pid'],'cover'=>'yes')); }
		if(isset($streams['THUMB'])&&!isset($streams['IMAGE'])) { echo $this->element('objthumb',array('thumb'=>$args['pid'])); }
		?>
		<h2><?php echo $data['objLabel']; ?></h2>
		<div class="leftspacer"></div>
		<?php echo $this->element('colsubcols'); ?>
</div>
<div class="right">
	<h2>Items in this Collection</h2>
	<ul>
		<?php foreach($data['items'] as $pid=>$title) { echo '<li>'.html_entity_decode($this->Html->link($title,'/items/view/'.$pid)).'</li>'; } ?>
	</ul>
</div>