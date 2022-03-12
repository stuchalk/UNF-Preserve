<div class="row px-xs-3 px-sm-3">
    <div class="col-sm-12 col-md-10 offset-md-1">
		<div class="row my-1">
			<div class="col-sm-12 col-md-5 col-lg-4 col-xl-3 order-2 order-md-1">
				<?php echo $this->element('preserve/layout'); ?>
			</div>
			<div class="col-sm-12 col-md-7 col-lg-8 col-xl-9 order-1 order-md-2">
				<div class="row">
					<!-- Display list of species -->
					<div class="col-sm-12" style="height: 110px;padding-top: 50px;">
						<h2>Mammal Inventory</h2>
					</div>
					<div class='col-sm-12'>
						<p>Scroll below or download the <?php echo $this->Html->link('Inventory PDF','/files/pdf/Mammals of the UNF Campus.pdf'); ?></p>
						<div class='list-group responsivediv'>
							<ul style="padding: 0;">
								<?php
								foreach($data as $pid=>$title) {
									echo '<li class="list-group-item">'.html_entity_decode($this->Html->link($title,'/mammals/view/'.$pid)).'</li>';
								}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
