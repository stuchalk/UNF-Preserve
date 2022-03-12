<?php
// Element that shows the stock photo like if there is one available
// Element variables: $species, $site
if ($species['image_url'] != 'NA' && $species['image_url'] != '') {
    echo $this->Html->image($species['image_url'], ['class' => 'col-sm-12 img-responsive','style'=>'padding: 0;','alt' => $species['cname']]);
	echo "<p class='small'>© 2020 " . $this->Html->link($site['name'], $site['homepage'], ['target' => '_blank']) . "</p>";
}
