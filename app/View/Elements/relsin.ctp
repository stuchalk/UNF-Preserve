<?php
// Element: Display relationships to this object from another object
// File: relsin.ctp
// Variables: $url, $width, $height
// v1.0 SJC 12/17/12
?>
<!-- element: relsin.ctp -->
<?php
if(!empty($data['relsin']))
{
	echo "<div class=\"righttextbox\">";
	$c=0;
	foreach($data['relsin'] as $pred=>$subs)
	{
		echo "<h4><i>".$pred."</i></h4><ul>";
		foreach($subs as $sub)
		{
			echo "<li id=\"item".$c."\">";
			$sub=str_replace('info:fedora/','',$sub);
			echo $this->Html->link($sub,'/objects/view/'.$sub);
			if($this->Session->check('Auth.User.id'))
			{
			?>
			<span class="doajax" onclick="doUpdate('/relationships/delete/<?php echo $sub; ?>','del<?php echo $c; ?>ViewForm','item<?php echo $c; ?>')"> (delete)</span>
			<?php
			echo $this->Form->create('del'.$c);
			echo $this->Form->input('Relationship.predicate',array('type'=>'hidden','value'=>lcfirst($pred)));
			echo $this->Form->input('Relationship.object',array('type'=>'hidden','value'=>$args['pid']));
			echo $this->Form->end();
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