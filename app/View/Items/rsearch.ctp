<div class="left">
	<h2>Search for Items</h2>
	<?php echo $this->element('leftstats'); ?>
</div>
<div class="right">
	<h2>Results for "<?php echo $args['value']; ?>"</h2>
	<ul>
		<?php
		if($args['value']=="*")
		{
			foreach($data as $title=>$count)
			{
				echo '<li>'.$title.' ('.$count.')</li>';
			}
		}
		else
		{
			foreach($data as $result)
			{
				if($result['type']=="Collection"):
					echo '<li>'.html_entity_decode($this->Html->link($result['title'],'/collections/view/'.$result['pid'])).'</li>';
				else:
					echo '<li>'.html_entity_decode($this->Html->link($result['title'],'/items/view/'.$result['pid'])).'</li>';
				endif;
			}
		}
		?>
	</ul>
</div>