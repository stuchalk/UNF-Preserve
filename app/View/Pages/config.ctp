<div class="left">
	<?php echo $this->element('objingest'); ?>
	<?php echo $this->element('contactinfo'); ?>
</div>
<div class="right">
	<h2>Configuration</h2>
	<p class="browse">1) Configure and start your Fedora instance (GSearch also if you have it installed)</p>
	<p class="browse">2) Edit <code>path-to-jaffedora/app/config/database.php</code> to add settings for you fedora3 database file and the Fedora repository.</p>
	<p class="browse">3) Enter values in the configuration file <code>path-to-jaffedora/app/config/jaffedora.php</code> specific to how Fedora and jafFedora are configured.</p>
	<p class="browse">4) Load in the test objects in <code>path-to-jaffedora/files</code> (not part of the normal CakePHP folder structure) using the "INGEST NEW OBJECT" form on the left.</p>
	<p class="browse">5) Read the documentation or click browse or search above to explore jafFedora in action.  You may add new objects in the test (default) namespace or in your own namespace if you change $config['pidns'] in the <code>path-to-jaffedora/app/config/jaffedora.php</code> config file.</p>
	<p class="browse">6) The default installation also includes controllers, models and views for accessing the repository objects as items and collections. See <?php echo $this->Html->link('items','/items'); ?>.</p>
</div>