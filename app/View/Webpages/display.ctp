<div class="row px-xs-3 px-sm-3">
    <div class="col-sm-12 col-md-10 offset-md-1">
		<div class="row my-1">
			<div class="col-sm-12 col-md-5 col-lg-4 col-xl-3 order-2 order-md-1">
				<?php echo $this->element('preserve/layout'); ?>
			</div>
			<div class="col-sm-12 col-md-7 col-lg-8 col-xl-9 order-1 order-md-2">
				<div class="col-sm-12" style="height: 110px;padding-top: 50px;">
					<h2><?php echo $data['title']; ?></h2>
				</div>
				<div id="Content" class="col-sm-12">
					<?php echo $data['content']; ?>
					<?php
					if($this->Session->check('Auth.User.id')) {
						echo '<div style="float: right;font-weight: bold;background-color: #F0F0F0;">ADMIN: ';
						echo $this->Html->link("Edit Page",'/webpages/edit/'.$args['id'].'/page');
						echo '</div>';
					}
					?>
				</div>
			</div>
		</div>
    </div>
</div>
