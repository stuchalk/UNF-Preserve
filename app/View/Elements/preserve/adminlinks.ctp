<?php
	// Element that shows the links to edit species info if logged in
	// Element variables: $type
?>

<?php
if($this->Session->check('Auth.User.id') && isset($type))
{
	echo '<div style="float: right;font-weight: bold;background-color: #F0F0F0;">ADMIN: ';
	echo $this->Html->link("Update Species Info",'/'.Inflector::pluralize($type).'/update/'.$args['id']).' • ';
	echo $this->Html->link("Update Photos",'/'.Inflector::pluralize($type).'/images/'.$args['id']);
	echo '</div>';
}
?>