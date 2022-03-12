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
						<h2>Reptiles/Amphibians Inventory</h2>
					</div>
					<div class="col-sm-12">
						Scroll below or download the <?php echo $this->Html->link('Inventory PDF','/files/pdf/Reptiles and Amphibians of the UNF Sawmill Slough Preserve.pdf'); ?>
						<div id="assets" class='list-group-flush responsivediv2'>
							<?php
							foreach($data as $pid=>$title) {
								echo html_entity_decode($this->Html->link($title,'/herps/view/'.$pid,['class'=>'list-group-item','alt'=>strtolower($title)]));
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
