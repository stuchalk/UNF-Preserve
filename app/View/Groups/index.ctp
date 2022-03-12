<div class="left">
	<h2>About Groups</h2>
	<p class="browse">To the right are campus programs, faculty, centers, divisions that have contributed collections to the archive. Click a group to view the collections that group has contributed.</p>
</div>
<div class="right">
	<h3>Contributing Groups</h3>
	<ul>
	<?php foreach($data as $pid=>$title) { echo '<li>'.$this->Html->link($title,'/groups/cols/'.str_replace(":info","",$pid)).'</li>'; } ?>
	</ul>
</div>