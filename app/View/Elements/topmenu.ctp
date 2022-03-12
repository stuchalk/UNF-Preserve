<?php
// Element: Site top menu
// File: topmenu.ctp
// Variables: None
// v1.0 SJC 12/17/12
?>
<!-- element: topmenu.ctp -->
<div class="topmenu">
	<div class="spacingsml">
		<div class="buttonsml"><?php echo $this->Html->link('Home','/',array('class'=>'linkwhite')); ?></div>
	</div>
	<div class="spacingvlrg">
		<div class="buttonvlrg">
			<?php echo $this->Html->link('UNF Environmental Center','http://www.unf.edu/ecenter',array('class'=>'linkwhite','target'=>'_blank')); ?>
		</div>
	</div>
	<div class="spacingsml">
		<div><?php //echo $this->Html->link('Search','/pages/search',array('class'=>'linkwhite')); ?></div>
	</div>
	<div class="spacingsml"><?php if($this->Session->check('Auth.User.id')) { echo "Logged in as: ".$this->Session->read('Auth.User.username'); }?>
	</div>
	<div class="spacingsml" style='width: 185px;'>
		<div class="buttonmed">
		<?php
			if($this->Session->check('Auth.User.id')):
				echo $this->Html->link('Logout','/users/logout',array('class'=>'linkwhite'));
			else:
				echo $this->Html->link('Admin Login','/users/login',array('class'=>'linkwhite'));
			endif;
		?>
		</div>
	</div>
</div>