<div class="left">
	<h2>jafFedora Error</h2>
</div>
<div class="right">
	<p>ERROR: <?php echo $error; ?></p>
	<p>URL: <?php echo $url; ?></p>
	<?php if(isset($params)&&!empty($params))	{ ?><p>PARAMS: <?php echo pr($params); ?><?php } ?>
	<?php if(isset($request))					{ ?><p>FEDORA REQUEST: <?php echo pr($request); ?><?php } ?>
	<?php if(isset($response))					{ ?><p>FEDORA RESPONSE: <?php echo pr($response); ?><?php } ?>
</div>