<div class="left">
	<h2>Object RI Search</h2>
</div>
<div class="right">
	<h2>Results of Search on "<?php echo $args['value']; ?>" in "<?php echo $args['field']; ?>"</h2>
	<ul>
		<?php
			if(!empty($data)):
				foreach($data as $hit) { echo $this->element('rsearchresult',array('hit'=>$hit)); }
			else:
				echo "No items found";
			endif;
		?>
	</ul>
</div>