<?php
// Element: Show a list of datastreams of an object
// File: stradmin.ctp
// Variables: $data
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: stradmin.ctp -->
<?php if($this->Session->check('Auth.User.id')) { ?>
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('stradmin');">Actions</div>
		<div class="clear"></div>
	</div>
	<div id="stradmin" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
		<?php
		$a=$this->action;
		echo ($a!="add") ? '<li>'.$this->Html->link('Add Another Stream','/datastreams/add/'.$args['pid']).'</li>': '';
		echo '<li>'.$this->Html->link('Download Stream','/datastreams/content/'.$args['pid'].'/'.$args['dsid'].'/download').'</li>';
		echo '<li>'.$this->Html->link('Inactivate Stream','/datastreams/state/'.$args['pid'].'/'.$args['dsid'].'?dsState=I').'</li>';
		echo '<li>'.$this->Html->link('Hide Stream','/datastreams/delete/'.$args['pid'].'/'.$args['dsid'].'?action=hide').'</li>';
		echo '<li>'.$this->Html->link('Delete Stream','/datastreams/delete/'.$args['pid'].'/'.$args['dsid'].'?action=purge',array('onclick'=>'confirmDelete(this);return false;')).'</li>';
		echo ($a!="update") ? '<li>'.$this->Html->link('Edit Stream','/datastreams/update/'.$args['pid'].'/'.$args['dsid']).'</li>': '';
		echo ($a!="view") ? '<li>'.$this->Html->link('View Stream ','/datastreams/view/'.$args['pid'].'/'.$args['dsid']).'</li>': '';
		echo '<li>'.$this->Html->link('View Object ','/objects/view/'.$args['pid']).'</li>';
		?>
		</ul>
	</div>
</div>
<?php } ?>