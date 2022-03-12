<?php
// Element: Show user prefs and logout link
// File: user.ctp
// Variables: None
// v1.0 SJC 12/17/12
?>
<!-- element: user.ctp -->
<?php $user=$this->Session->read('Auth.User')?>
<p class="topbar">
	Welcome <?php echo $user['firstname']; ?> • 
	<?php echo $this->Html->link('Preferences','/users/prefs')?> • 
	<?php echo $this->Html->link('Logout','/users/logout')?>
</p>