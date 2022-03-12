<script type="application/javascript">
    $( document ).ready(function() {
        $(".browse").click(function(e) {
            let letter=$(this).attr('char');
            $('.letter').hide();
            $('.letter ul li').show();
            $('#char' + letter).show();
        });
        $("#search").on('input',function(){
            let val=$(this).val();
            if(val.length<3){
                $(".letter li").show();
                $(".letter").hide();
                $("#charA").show();
            } else {
                $(".letter").show();
                $(".letter li").show();
                $("#assets li:not(:contains('" + val + "'))").hide();
            }
        });
    });
</script>
<?php
$chars = array_keys($data);$charstr = "";
foreach ($chars as $letter) { $charstr .= '"'.$letter.'",'; }
?>
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
						<h2>Plant Inventory</h2>
					</div>
					<div class="col-sm-12">
						<div class="panel panel-info">
							<div class="panel-heading" style="padding: 5px 10px;">
								<div class="input-group" role="group" aria-label="letters">
									<?php echo $this->Form->input('search',['type'=>'text','label'=>false,'div'=>false,'placeholder'=>'Search...','class'=>'form-control input-sm col-2','style'=>'padding: 0 5px;']); ?>
									<div class="btn-group">
										<?php
										foreach ($data as $char => $items) {
											echo '<button char="'.$char.'" class="btn btn-success btn-sm navbar-btn browse" style="padding: 5px; margin: 0;" type="button" style="cursor: pointer;" href="#">'.$char.'</button>';
										}
										?>
									</div>
									<div class="btn-group">
										<?php echo "<a class='btn btn-info btn-sm navbar-btn' style='margin: 0 10px;' href='/files/pdf/Plants of the UNF Sawmill Slough Preserve.pdf'>PDF</a>"; ?>
									</div>
									<div class="btn-group">
										<button type="button" class="btn btn-success btn-sm navbar-btn dropdown-toggle" style="margin: 0;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Sort <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<li><a class="dropdown-item" href="/plants/index/cname">By Common Name</a></li>
											<li><a class="dropdown-item" href="/plants/index/sname">By Scientific Name</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div id="assets" class="list-group-flush responsivediv">
								<?php
								foreach ($data as $char => $parray) {
									if($char==$chars[0]) {
										echo "<div id='char".$char."' class='letter' style='display: block;'>";
									} else {
										echo "<div id='char".$char."' class='letter' style='display: none;'>";
									}
									foreach ($parray as $pid => $title) {
										echo html_entity_decode($this->Html->link($title, '/plants/view/' . $pid,['class'=>'list-group-item','alt'=>strtolower($title)]));
									}
									echo "</div>";
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
