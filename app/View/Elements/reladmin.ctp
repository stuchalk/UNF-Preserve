<?php
// Element: Show a list of admin functions for relationships
// File: reladmin.ctp
// Variables: $vis
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: reladmin.ctp -->
<?php if($this->Session->check('Auth.User.id')) { ?>
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('reladmin');">Actions</div>
		<div class="clear"></div>
	</div>
	<div id="reladmin" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
		<?php
		$a=$this->action;
		echo ($a!="add") ? '<li>'.$this->Html->link('Add Relationship','/relationships/add/'.$args['pid']).'</li>': '';
		if(isset($data['content']['exturl'])) { echo '<li>'.$this->Html->link('Download RDF',$data['content']['exturl']).'</li>'; }
		echo ($a!="update") ? '<li>'.$this->Html->link('Edit Relationships','/relationships/update/'.$args['pid']).'</li>': '';
		echo '<li>'.$this->Html->link('Delete RELS-EXT','/datastreams/delete/'.$args['pid'].'/RELS-EXT').'</li>';
		echo '<li>'.$this->Html->link('Return to Object','/objects/view/'.$args['pid']).'</li>';
		?>
		</ul>
	</div>
</div>
<?php } ?>