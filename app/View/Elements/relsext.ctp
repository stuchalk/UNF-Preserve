<?php
// Element: Display relationships of an object
// File: relsext.ctp
// Variables: $url, $width, $height
// v1.0 SJC 12/17/12
?>
<!-- element: relsext.ctp -->
<?php
if(isset($data['relsout']['HasModel'])) { unset($data['relsout']['HasModel']); }// No need to show Fedora sdef rels
if(!empty($data['relsout']))
{
	echo "<div class=\"righttextbox\">";
	$c=0;
	foreach($data['relsout'] as $pred=>$objs)
	{
		echo "<h4><i>".lcfirst($pred)."</i></h4><ul>";
		foreach($objs as $obj)
		{
			echo "<li id=\"item".$c."\">";
			if(stristr($obj,"info:fedora")) // Object
			{
				$obj=str_replace('info:fedora/','',$obj);
				?>
				<?php echo $this->Html->link($obj,'/objects/view/'.$obj); ?>
				<?php
				if($this->Session->check('Auth.User.id'))
				{
				?>
				<span class="doajax" onclick="doUpdate('/relationships/delete/<?php echo $args['pid']; ?>','del<?php echo $c; ?>ViewForm','item<?php echo $c; ?>')"> (delete)</span>
				<?php
				echo $this->Form->create('del'.$c);
				echo $this->Form->input('Relationship.predicate',array('type'=>'hidden','value'=>lcfirst($pred)));
				echo $this->Form->input('Relationship.object',array('type'=>'hidden','value'=>$obj));
				echo $this->Form->end();
				}
			}
			else // Literal
			{
				echo $obj;
				if($this->Session->check('Auth.User.id'))
				{
				?>
				<span class="doajax" onclick="doUpdate('/relationships/delete/<?php echo $args['pid']; ?>','del<?php echo $c; ?>ViewForm','item<?php echo $c; ?>')"> (delete)</span>
				<?php
				echo $this->Form->create('del'.$c);
				echo $this->Form->input('Relationship.predicate',array('type'=>'hidden','value'=>lcfirst($pred)));
				echo $this->Form->input('Relationship.literal',array('type'=>'hidden','value'=>$obj));
				echo $this->Form->end();
				}
			}
			$c++;
			echo "</li>";
		}
		echo "</ul>";
	}
	echo "</div>";
}
else
{
	echo "<div class=\"righttextbox\">None</div>";
}
?>