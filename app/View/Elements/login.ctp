<?php
// Element: Show login form
// File: objcols.ctp
// Variables: None
// v1.0 SJC 12/17/12
?>
<!-- element: login.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle">Admin Login</div>
		<div class="clear"></div>
	</div>
	<div class="leftdivbody">
		<?php
		echo $form->create('User',array('action'=>'login'));
		echo $form->input('username');
		echo $form->input('password');
		echo $form->end('Login');
		?>
	</div>
</div>