<div class="left">
	<h2>Fedora GSearch</h2>
</div>
<div class="right">
	<h2>Results</h2>
	<?php
		foreach($data['results'] as $hit)
		{
			if(isset($hit['snippet'])) { echo $this->element('gsearchresult',array('hit'=>$hit)); }
		}
	?>
	<p>
	<?php
		if($data['hits']>$args['pagesize'])
		{
			$prevpagestart=($args['pagesize']*($args['page']-2))+1;
			$nextpagestart=($args['pagesize']*$args['page'])+1;
			if($prevpagestart>0) 			{ echo $this->Html->link('Prev page','/services/gsearch/'.$args['field'].'/'.$args['value'].'/'.($args['page']-1).'/'.$args['pagesize']).' | '; }
			echo "Page ".$args['page'];
			if($nextpagestart<($data['hits']+1))	{ echo ' | '.$this->Html->link('Next page','/services/gsearch/'.$args['field'].'/'.$args['value'].'/'.($args['page']+1).'/'.$args['pagesize']); }
		}
	?>
	</p>
</div>
