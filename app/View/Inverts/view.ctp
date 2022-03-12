<div class="row px-xs-3 px-sm-3">
    <div class="col-sm-12 col-md-10 offset-md-1">
		<div class="row my-1">
			<div class="col-sm-12 col-md-5 col-lg-4 col-xl-3 order-2 order-md-1">
				<?php echo $this->element('preserve/layout'); ?>
			</div>
			<div class="col-sm-12 col-md-7 col-lg-8 col-xl-9 order-1 order-md-2">
				<div class="row">
					<!-- Display species data -->
					<div class="col-sm-12" style="height: 110px;padding-top: 50px;">
						<h2>Invertebrate Inventory</h2>
					</div>
					<div class="col-sm-12">
						<h4><i><?php echo $invert['sname']; ?></i></h4>
						<p><b>Common Name:</b> <?php echo $invert['cname']; ?><br/>
							<b>Order:</b> <?php echo $invert['order']; ?><br/>
							<b>Family:</b> <?php echo $invert['family']; ?><br/>
							<?php if ($this->Session->check('Auth.User.id')) {
								echo "<b>Last Updated:</b> " . date("F j, Y, g:i a", strtotime($invert['updated'])) . "<br />";
							} ?>
							<?php if ($invert['comment'] != "") {
								echo "<b>Comments:</b> " . $invert['comment'] . "<br />";
							} ?>
						</p>
					</div>

					<!-- Display example photo if available -->
					<div class="col col-sm-6 col-lg-5">
						<!-- External website link -->
						<?php if ($invert['url'] != "") {
							echo "<h5><i>Click ".$this->Html->link('here', str_replace($site['ns'] . ":", $site['url'], $invert['url']), ['target' => '_blank'])." for more about this species</i></h5>";
						} ?>
						<?php if ($invert['image_url'] != "") {
							echo $this->element('preserve/stockphoto', ['species' => $invert, 'site' => $site]);
						} ?>
					</div>

					<!-- Display any archive photos -->
					<div class="col col-sm-6 col-lg-5">
						<?php
						if (!empty($photos)) {
							echo "<h5><i>Photographs from the Preserve</i></h5>";
							echo $this->element('preserve/carousel', ['photos' => $photos]);
						}
						?>
					</div>

					<!-- Link to admin species (only shows if logged in) -->
					<?php //echo $this->element('preserve/adminlinks', ['type' => 'invert']); ?>
				</div>
			</div>
		</div>
	</div>
</div>
