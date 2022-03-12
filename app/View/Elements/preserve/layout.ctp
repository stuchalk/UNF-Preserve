<?php
// Element: Show the list of collections
// File: preserve.ctp
// Variables: $args['pid'] (from view), $vis
// v1.0 SJC 11/24/12
if (!isset($vis)) {
    $vis = true;
}
$stats = $this->requestAction('/preserve/index');
?>
<!-- element: preserve.ctp -->
<div class="img-responsive text-center d-none d-md-block" style="margin-bottom: 10px;">
    <?php echo $this->Html->image('preserve_logo.png', ['width' => '150px', 'alt' => 'UNF Sawmill Slough Preserve', 'url' => 'https://preserve.ecenter.domains.unf.edu/']); ?>
</div>
<div class="card card-info">
    <div class="card-header py-1">
        <h6 class="card-title my-1">About</h6>
    </div>
    <div class="card-body py-1 px-1">
        <ul class="list-group-flush px-0 my-0">
        <?php
        echo "<li class='list-group-item py-1'>".$this->Html->link('The History of the Preserve', '/history')."</li>";
        echo "<li class='list-group-item py-1'> -- ".$this->Html->link('The Conservation Club', '/club')."</li>";
        echo "<li class='list-group-item py-1'>".$this->Html->link('Prescribed Burns', '/burns')."</li>";
        echo "<li class='list-group-item py-1'>".$this->Html->link('Weeds and Exotic Pest Plants', '/exotics')."</li>";
        echo "<li class='list-group-item py-1'>".$this->Html->link('Habitats on the Preserve', '/habitats')."</li>";
        echo "<li class='list-group-item py-1'>".$this->Html->link('Species of Special Concern', '/special')."</li>";
        echo "<li class='list-group-item py-1'>".$this->Html->link('Scientific Research', '/research')."</li>";
        echo "<li class='list-group-item py-1'>".$this->Html->link('Prohibited Uses of the Preserve','http://www.unf.edu/physicalfacilities/Sawmill_Slough_Preserve.aspx',['target'=>'_blank'])."</li>";
        echo "<li class='list-group-item py-1'>".$this->Html->link('The Robert Loftin Trails','http://www.unf.edu/recwell/ecoadventure/Trails/',['target' => '_blank'])."</li>";
		echo "<li class='list-group-item py-1'>".$this->Html->link('Management Plan','/management')."</li>";
        ?>
        </ul>
    </div>
</div>
<div class="card card-primary mt-1">
    <div class="card-header py-1">
        <h6 class="card-title my-1">Species Inventories</h6>
    </div>
    <div class="card-body py-1 px-1">
		<ul class="list-group-flush px-0 my-0">
            <?php
            foreach ($stats['invs'] as $type => $count) {
                // Ignores any inventory with no data
                if($type=='reptiles/Amphibians'): echo "<li class='list-group-item py-1'>".$this->Html->link(ucfirst($type)." (".$count.")",'/herps')."</li>";
                elseif ($type=='invertebrates'): echo "<li class='list-group-item py-1'>".$this->Html->link(ucfirst($type)." (".$count.")",'/inverts')."</li>";
                else: echo "<li class='list-group-item py-1'>".$this->Html->link(ucfirst($type)." (" . $count . ")",'/'.$type)."</li>";
                endif;
            }
            ?>
		</ul>
    </div>
</div>
<?php if ($this->Session->check('Auth.User.id')) { ?>
    <div class="card card-danger">
        <div class="card-header">
            <div class="card-title">Photo Collections</div>
        </div>
        <div class="card-body">
            <ul class="card-ul">
            <?php
            foreach ($stats['cols'] as $pid => $col) {
                if ($col['count'] > 0) {
                    echo "<li>" . $this->Html->link(str_replace("Preserve: ", "", $col['title']) . " (" . $col['count'] . ")", '/collections/view/' . $pid) . " </li>";
                }
            }
            ?>
            </ul>
        </div>
    </div>
<?php } ?>
