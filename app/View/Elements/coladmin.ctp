<?php
// Element: Outputs admin functions for collections
// File: coladmin.ctp
// Variables: $vis
// v1.0 SJC 11/24/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: coladmin.ctp -->
<?php if($this->Session->check('Auth.User.id')) { ?>
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('coladmin');">Admin</div>
		<div class="clear"></div>
	</div>
	<div id="coladmin" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
	<ul>
		<li><?php echo $this->Html->link('Add a New Collection','/collections/add'); ?></li>
		<li><?php echo $this->Html->link('Add a New Item','/items/add/'.$args['pid']); ?></li>
		<?php
		// Admin functions
		$collections=$this->requestAction('/collections/index');
		$vlist=$mlist=$dlist=array();
		foreach($collections as $pid=>$col)
		{
			if(strlen($col)>22) { $col=substr($col,0,22)."..."; }
			$vlist['/'.Configure::read('jaf.pidns').'/collections/view/'.$pid]=$col;
			$mlist['/'.Configure::read('jaf.pidns').'/collections/update/'.$pid]=$col;
			$dlist['/'.Configure::read('jaf.pidns').'/collections/delete/'.$pid]=$col;
		}
		?>
		<li><?php echo $this->Form->input('showcols',array('type'=>'select','options'=>$vlist,'label'=>false,'empty'=>'Show...','onchange'=>'document.location.href=this[selectedIndex].value;')); ?></li>
		<li><?php echo $this->Form->input('updatecols',array('type'=>'select','options'=>$mlist,'label'=>false,'empty'=>'Update...','onchange'=>'document.location.href=this[selectedIndex].value;')); ?></li>
		<li><?php echo $this->Form->input('deletecols',array('type'=>'select','options'=>$dlist,'label'=>false,'empty'=>'Delete...','onchange'=>'document.location.href=this[selectedIndex].value;')); ?></li>
		<li><?php echo $this->Html->link('Logout','/users/logout'); ?></li>
	</ul>
	</div>
</div>
<?php } ?>