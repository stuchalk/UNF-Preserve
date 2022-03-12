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
						<h2>Plant Inventory</h2>
					</div>
					<div class="col-sm-12">
						<h3><i><?php echo $lichen['sname']; ?></i></h3>
						<p><b>Common Name:</b> <?php echo $lichen['cname']; ?><br/>
							<b>Type:</b> <?php echo $lichen['type']; ?><br/>
							<b>Family:</b> <?php echo $lichen['family']; ?><br/>
							<?php if ($this->Session->check('Auth.User.id')) {
								echo "<b>Last Updated:</b> " . date("F j, Y, g:i a", strtotime($lichen['updated'])) . "<br />";
							} ?>
							<?php if ($lichen['comment'] != "") {
								echo "<b>Comments:</b> " . $lichen['comment'] . "<br />";
							} ?>
						</p>
					</div>

					<!-- Display example photo if available -->
					<div class="col col-sm-6 col-lg-5">
						<!-- External website link -->
						<?php if ($lichen['url'] != "") {
							echo "<h5><i>Click ".$this->Html->link('here', str_replace($site['ns'] . ":", $site['url'], $lichen['url']), ['target' => '_blank'])." for more about this species</i></h5>";
						} ?>
						<?php if ($lichen['image_url'] != "") {
							echo $this->element('preserve/stockphoto', ['species' => $lichen, 'site' => $site]);
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
				</div>

				<!-- Link to admin species (only shows if logged in) -->
				<?php //echo $this->element('preserve/adminlinks', ['type' => 'lichen']); ?>
			</div>
		</div>
    </div>
</div>
