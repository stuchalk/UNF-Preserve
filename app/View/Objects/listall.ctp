<div class="left">
	<h2>All Digital Objects</h2>
	<p class="browse">Click on the titles of the objects listed on the right to view the objects.  You can ingest new objects below, either by uploading a FOXML, ATOM or METS file or by clicking on the 'Add a New Object' link below.</p>
	<div class="leftspacer"></div>
	<?php echo $this->element('objingest'); ?>
	<p class="browse"><?php echo $this->Html->link('Add a New Object','/objects/add'); ?> (must login)</p>
	<div class="leftspacer"></div>
	<p class="smallitalic">NOTE: Make sure the objects you add are in the default namespace as defined in the <code>jaffedora.php</code> config file so they show up in this list<p>
</div>
<div class="right">
	<h2>Repository Objects in the '<?php echo Configure::read('jaf.pidns'); ?>' Namespace</h2>
	<ul><?php foreach($data['results'] as $key=>$item) { echo '<li>'.$this->Html->link($item,'/objects/view/'.$key).'</li>'; } ?></ul>
</div>