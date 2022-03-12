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
						<h2>Fish Inventory</h2>
					</div>
					<div class="col-sm-12">
						<h3><i><?php echo $fish['sname']; ?></i></h3>
						<p><b>Common Name:</b> <?php echo $fish['cname']; ?><br />
							<b>Group:</b> <?php echo $fish['group']; ?><br />
							<b>Family:</b> <?php echo $fish['family']; ?><br />
							<?php if($this->Session->check('Auth.User.id')) {
								echo "<b>Last Updated:</b> ".date("F j, Y, g:i a",strtotime($fish['updated']))."<br />";
							} ?>
							<?php if($fish['comment']!="") {
								echo "<b>Comments:</b> ".$fish['comment']."<br />";
							} ?>
						</p>
					</div>

					<!-- External website link -->
					<div class="col col-sm-6 col-lg-5">
						<?php
						if($fish['url']!="") {
							echo "<h5><i>Click ".$this->Html->link('here', str_replace($site['ns'] . ":", $site['url'], $fish['url']), ['target' => '_blank'])." for more about this species</i></h5>";
						}
						?>
						<!-- Display example photo if available -->
						<?php
						if($fish['image_url']!="") {
							echo $this->element('preserve/stockphoto',['species'=>$fish,'site'=>$site]);
						}
						?>
					</div>

					<!-- Display any archive photos -->
					<div class="col col-sm-6 col-lg-5">
						<?php
						if (!empty($photos)) {
							echo "<h5>Photographs from the Preserve</h5>";
							echo $this->element('preserve/carousel', ['photos' => $photos]);
						}
						?>
					</div>
				</div>

				<!-- Link to admin species (only shows if logged in) -->
				<?php //echo $this->element('preserve/adminlinks', ['type' => 'fish']); ?>
			</div>
		</div>
	</div>
</div>
