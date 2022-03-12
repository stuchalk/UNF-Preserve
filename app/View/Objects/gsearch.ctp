<div class="left">
	<h2>Object GSearch</h2>
</div>
<div class="right">
	<h2>Results for Fulltext Search on "<?php echo $args['term']; ?>"</h2>
	<ul>
		<?php
			if(!empty($data)):
				foreach($data['results'] as $hit) { if(isset($hit['snippet'])) { echo $this->element('gsearchresult',array('hit'=>$hit)); } }
			else:
				echo "No items found";
			endif;
		?>
	</ul>
</div>