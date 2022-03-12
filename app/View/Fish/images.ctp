<?php
	// Data setup - $data and $args coming from controller
	$fish=$data['Fish'];
	list($ns,$id)=explode(":",$fish['url']);
	$website=$this->requestAction('/websites/view/'.$ns);
	$site=$website['Website'];
?>

<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<!-- Display example photo if available -->
	<?php echo $this->element('preserve/stockphoto',['species'=>$fish,'site'=>$site]); ?>
	
	<!-- Display species data -->
	<div id="topright" style="float: left;">
		<h2>Fish Inventory</h2>
		<h3><i><?php echo $fish['sname']; ?></i></h3>
		<p><b>Common Name:</b> <?php echo $fish['cname']; ?><br />
		<b>Group:</b> <?php echo $fish['group']; ?><br />
		<b>Family:</b> <?php echo $fish['family']; ?><br />
		<b>Last Updated:</b> <?php echo date("F j, Y, g:i a",strtotime($fish['updated'])); ?>
		<?php if($fish['comment']!="") { echo "<br /><b>Comments:</b> ".$fish['comment']; } ?>
		</p>
		<?php
			if($fish['url']!="")
			{
				list($ns,$id)=explode(":",$fish['url']);
				echo "<p>More information on this species can be found ".$this->Html->link('here',$site['url'].$id,['target'=>'_blank'])."</p>";
			}
		?>
		<p>Set this species as protected/open</p>
		<?php
			$script="setProtection('".$fish['id']."',this.value)";
			echo "<div>".$this->Form->input('protected',['type'=>'radio','value'=>$fish['protected'],'div'=>false,'options'=>['0'=>'Open','1'=>'Protected'],'legend'=>false,'onchange'=>$script])."</div>";
		?>
	</div>
	<div class="clear"></div>

	<p>&nbsp;</p>
	<h3>Existing Photographs</h3>
	<?php
	if(!empty($photos))
	{
        $pcount=count($photos);
        $type="fish";$name=$fish['cname'];
        foreach($photos as $photo) { echo $this->element('preserve/unfphoto',['divid'=>'photo'.$photo['pid'],'type'=>$type,'name'=>$name,'photo'=>$photo]); }
        echo "<div class='clear'></div>";
	}
	else
	{
		$pcount=0;
		echo "<i>There are currently no photographs for this invertebrate - add some below!</i>";
	}
	?>
	<div class='clear'></div>
	<p>&nbsp;</p>
	<?php echo $this->element('preserve/addphotoform',['type'=>'fish','id'=>$fish['id'],'pcount'=>$pcount,'col'=>$col]); ?>
</div>