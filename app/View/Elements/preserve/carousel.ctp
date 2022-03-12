<?php
//debug($photos);
?>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
		<?php foreach($photos as $idx=>$photo) { ?>
			<li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $idx; ?>"<?php if($idx==0) { echo ' class="active"'; } ?></li>
		<?php } ?>
	</ol>
	<div class="carousel-inner">
		<?php
		foreach($photos as $idx=>$photo) {
			?>
			<div class="carousel-item<?php if($idx==0) { echo " active"; } ?>" data-interval="10000">
				<img src="<?php echo "/".$photo['Stream'][0]['path'] ?>" class="d-block w-100" alt="photo<?php echo $idx ?>">
				<div class="carousel-caption d-none d-md-block">
					<h5><?php echo "© ".$photo['Asset']['creator']; ?></h5>
				</div>
			</div>
		<?php } ?>
	</div>
	<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
