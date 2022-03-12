<div class="row px-xs-3 px-sm-3">
	<div class="col-xs-12 col-md-10 offset-md-1">
		<div class="row my-1">
			<div class="col-sm-12 col-md-5 col-lg-4 col-xl-3 order-2 order-md-1">
				<?php echo $this->element('preserve/layout'); ?>
			</div>
			<div class="col-sm-12 col-md-7 col-lg-8 col-xl-9 order-1 order-md-2">
				<div class="row">
					<!-- Display species data -->
					<div class="col-sm-12" style="height: 110px;padding-top: 50px;">
						<h2>Bird Inventory</h2>
					</div>
					<div class="col-sm-12">
						<h3><i><?php echo $bird['sname']; ?></i></h3>
						<p><b>Common Name:</b> <?php echo $bird['cname']; ?><br/>
							<b>Group:</b> <?php echo $bird['group']; ?><br/>
							<b>Family:</b> <?php echo $bird['family']; ?><br/>
							<?php if ($this->Session->check('Auth.User.id')) {
								echo "<b>Last Updated:</b> " . date("F j, Y, g:i a", strtotime($bird['updated'])) . "<br />";
							} ?>
							<?php if ($bird['comment'] != "") {
								echo "<b>Comments:</b> " . $bird['comment'] . "<br />";
							} ?>
						</p>
						<!-- External website link -->
						<?php if ($bird['url'] != "") {
							echo "<h4><i>Click ".$this->Html->link('here', str_replace($site['ns'] . ":", $site['url'], $bird['url']), ['target' => '_blank'])." for more about this species</i></h5>";
						} ?>
					</div>

					<!-- Display example photo if available -->
					<div class="col col-sm-6 col-lg-5">
						<?php
						if ($bird['image_url'] != "") {
							echo $this->element('preserve/stockphoto', ['species' => $bird, 'site' => $site]);
						}
						?>
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
				<?php //echo $this->element('preserve/adminlinks', ['type' => 'bird']); ?>
			</div>
		</div>
	</div>
</div>
