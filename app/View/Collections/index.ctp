<div class="left">
	<h2>About Collections</h2>
	<p class="browse">Collections in the archive can be of any type (images, video, audio, documents etc.) centered around a particular research topic, region of campus, or environmental focus area.</p>
</div>
<div class="right">
	<h3>Archived Collections</h3>
	<ul>
	<?php foreach($data as $pid=>$title) { echo '<li>'.$this->Html->link($title,'/collections/view/'.str_replace(":info","",$pid)).'</li>'; } ?>
	</ul>
</div>