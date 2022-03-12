<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>Inventory Websites</h2>
	<?php pr($data); ?>
    <h3 style="cursor: pointer;" onclick="document.location.href='/websites/add';">Add Website</h3>
</div>