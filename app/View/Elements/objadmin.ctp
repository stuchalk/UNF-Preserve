<?php
// Element: Show a list of datastreams of an object
// File: objadmin.ctp
// Variables: $data
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: objadmin.ctp -->
<?php if($this->Session->check('Auth.User.id')) { ?>
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('objadmin');">Actions</div>
		<div class="clear"></div>
	</div>
	<div id="objadmin" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
			<?php echo '<li>'.$this->Html->link('Add Datastream','/datastreams/add/'.$args['pid']).'</li>'; ?>
			<?php echo '<li>'.$this->Html->link('Add Relationship','/relationships/add/'.$args['pid']).'</li>'; ?>
			<?php echo '<li>'.$this->Html->link('Download Object (XML)','/repository/export/'.$args['pid']).'</li>'; ?>
			<?php echo '<li>'.$this->Html->link('Migrate Object (XML)','/repository/export/'.$args['pid'].'/foxml11/migrate').'</li>'; ?>
			<?php echo '<li>'.$this->Html->link('Archive Object (XML)','/repository/export/'.$args['pid'].'/foxml11/archive').'</li>'; ?>
			<?php echo '<li>'.$this->Html->link('Delete Object','/objects/delete/'.$args['pid'],array('onclick'=>'confirmDelete(this);return false;')).'</li>'; ?>
			<?php echo '<li>'.$this->Html->link('Edit/Update Object','/objects/update/'.$args['pid']).'</li>'; ?>
			<?php echo '<li>'.$this->Html->link('Validate Object','/repository/valid/'.$args['pid']).'</li>'; ?>
			<?php echo '<li>'.$this->Html->link('Show All Objects','/objects/listall').'</li>'; ?>
		</ul>
	</div>
</div>
<?php } ?>