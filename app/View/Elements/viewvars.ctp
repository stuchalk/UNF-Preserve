<?php
// Element: Shows view variables - $data and $args
// File: viewvars.ctp
// Variables: None
// v1.0 SJC 11/24/12
?>
<!-- element: viewvars.ctp -->
<h3 onclick="togglevis('data');" style="cursor: pointer;">See View Variables (toggle)</h3>
<div id="data" class="righttextbox" style="display: none;">
	<h4>Request Variables ($args array)</h4>
	<?php pr($args); ?>
	<h4>Returned Data ($data array)</h4>
	<?php pr($data); ?>
</div>