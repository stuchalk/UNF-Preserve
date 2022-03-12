<script type="application/javascript">
    $(document).ready(function () {
        $(".browse").click(function (e) {
            var order = $(this).attr('order');
            $('.order').hide();
            $('.order ul li').show();
            $('#' + order).show();
        });
    });
</script>

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
						<h2>Invertebrate Inventory</h2>
					</div>
					<div class="col-sm-12">
						<p>Browse by Order (left) or download the <?php echo $this->Html->link('Inventory PDF', '/files/pdf/Insects and Invertebrates of the UNF Sawmill Slough Preserve.pdf') ?></p>
					<?php
					//pr($data);exit;
					$temp = $data;$orders = [];
					foreach ($temp as $order => $list) { $orders[] = $order; }
					?>
						<div class="row">
							<div id='menu' class='col col-sm-4'>
								<div class='list-group'>
									<ul style="padding: 0;">
									<?php
									foreach ($orders as $order) {
										echo "<li class='list-group-item browse' style='padding: 3px 10px;' order='".$order."' style='cursor: pointer;'>Order: ".$order."</li>";
									}
									?>
									</ul>
								</div>
							</div>

							<div id='orders' class='col col-sm-8'>
								<?php
								foreach ($data as $order => $parray) {
									if ($order == $orders[0]) {
										$style = "block";
									} else {
										$style = "none";
									}
									?>
									<div id='<?php echo $order; ?>' class='panel panel-success order' style='display: <?php echo $style; ?>;'>
										<div class="panel-heading" style="padding: 0 10px;">
											<?php echo "<h4 style='margin: 0;'>Order: " . $order . "</h4>"; ?>
										</div>
										<div class="list-group responsivediv">
											<ul style="padding: 0;">
											<?php
											foreach ($parray as $pid => $title) {
												echo '<li class="list-group-item">' . html_entity_decode($this->Html->link($title, '/inverts/view/' . $pid)) . '</li>';
											}
											?>
											</ul>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>
</div>
