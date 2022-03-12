<script type="application/javascript">
    $( document ).ready(function() {
        $("#search").on('input',function(){
            let val=$(this).val();
            if(val.length<3){
                $("#assets a").show();
            } else {
            	$("#assets a:not([alt*=" + val.toLowerCase() + "])").hide();
            }
        });
    });
</script>
<div class="row px-xs-3 px-sm-3">
	<div class="col-xs-12 col-md-10 offset-md-1">
		<div class="row my-1">
			<div class="col-sm-12 col-md-5 col-lg-4 col-xl-3 order-2 order-md-1">
				<?php echo $this->element('preserve/layout'); ?>
			</div>
			<div class="col-sm-12 col-md-7 col-lg-8 col-xl-9 order-1 order-md-2">
				<div class="row">
					<!-- Display list of species -->
					<div class="col-sm-12" style="height: 110px;padding-top: 50px;">
						<h2>Bird Inventory</h2>
					</div>
					<div class="col-sm-12">
						<div class="panel panel-info">
							<div class="panel-heading" style="padding: 5px 10px;">
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<a class="btn btn-outline-secondary" type="button" href="/files/pdf/Birds of the UNF Sawmill Slough Preserve.pdf">PDF</a>
										<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sort</button>
										<div class="dropdown-menu">
											<a class="dropdown-item" href="/birds/index/cname">By Common Name</a>
											<a class="dropdown-item" href="/birds/index/sname">By Scientific Name</a>
										</div>
									</div>
									<input id="search" type="text" class="form-control" placeholder="Search..." aria-label="Search"/>
								</div>
							</div>
							<div id="assets" class="card-body list-group-flush responsivediv">
								<?php
								foreach($data as $char => $parray) {
									foreach($parray as $pid => $title) {
										echo html_entity_decode($this->Html->link($title,'/birds/view/'.$pid,['class'=>'list-group-item','alt'=>strtolower($title)]));
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
