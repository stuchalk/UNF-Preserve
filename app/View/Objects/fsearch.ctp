<div class="left">
	<h2>Object Search</h2>
</div>
<div class="right">
	<h2>Results for Search on "<?php echo $args['term']; ?>"</h2>
	<ul>
		<?php
			if(!empty($data)):
				foreach($data['results'] as $hit) { echo $this->element('fsearchresult',array('hit'=>$hit)); }
			else:
				echo "No items found";
			endif;
		?>
	</ul>
</div>