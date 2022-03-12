<div class="left">
	<h2>Object Validation</h2>
</div>
<div class="right">
	<?php
	if($data['@valid']=="true"):	echo "<h2>This object is valid</h2>";
	else:							echo "<h2>This object has validation errors</h2>";
									if(!empty($data['DatastreamProblems']))
									{
										if(!isset($data['DatastreamProblems']['Datastream'][0]))
										{
											$data['DatastreamProblems']['Datastream'][]=$data['DatastreamProblems']['Datastream'];
											unset($data['DatastreamProblems']['Datastream']['datastreamID']);
											unset($data['DatastreamProblems']['Datastream']['problem']);
										}
										foreach($data['DatastreamProblems']['Datastream'] as $stream)
										{
											echo "<p>".$stream['datastreamID']. " => Error: ".$stream['problem']."</p>";
										}
									}
	endif;
	?>
	<?php echo $this->element('viewvars'); ?>
</div>