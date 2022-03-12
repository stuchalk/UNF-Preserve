<?php
	// Data setup - $data and $args coming from controller
	$mammal=$data['Mammal'];
	list($ns,$id)=explode(":",$mammal['url']);
	$website=$this->requestAction('/websites/view/'.$ns);
	$site=$website['Website'];
?>

<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<!-- Display example photo if available -->
	<?php echo $this->element('preserve/stockphoto',['species'=>$mammal,'site'=>$site]); ?>
	
	<!-- Display species data -->
	<div id="topright" style="float: left;">
		<h2>Mammal Inventory</h2>
		<h3><i><?php echo $mammal['sname']; ?></i></h3>
		<p><b>Common Name:</b> <?php echo $mammal['cname']; ?><br />
		<b>Family:</b> <?php echo $mammal['family']; ?><br />
		<b>Species:</b> <?php echo $mammal['species']; ?><br />
		<b>Status:</b> <?php echo $mammal['status']; ?><br />
		<b>Distribution:</b> <?php echo $mammal['distribution']; ?><br />
		<b>Last Updated:</b> <?php echo date("F j, Y, g:i a",strtotime($mammal['updated'])); ?>
		<?php if($mammal['comment']!="") { echo "<br /><b>Comments:</b> ".$mammal['comment']; } ?>
		</p>
		<?php
			if($mammal['url']!="")
			{
				list($ns,$id)=explode(":",$mammal['url']);
				echo "<p>More information on this species can be found ".$this->Html->link('here',$site['url'].$id,['target'=>'_blank'])."</p>";
			}
		?>
		<p>Set this species as protected/open</p>
		<?php
			$script="setProtection('".$mammal['id']."',this.value)";
			echo "<div>".$this->Form->input('protected',['type'=>'radio','value'=>$mammal['protected'],'div'=>false,'options'=>['0'=>'Open','1'=>'Protected'],'legend'=>false,'onchange'=>$script])."</div>";
		?>
	</div>
	<div class="clear"></div>

	<p>&nbsp;</p>
	<h3>Existing Photographs</h3>
	<?php
	if(!empty($photos))
	{
		$pcount=count($photos);
		$type="mammal";$name=$mammal['cname'];
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
	<?php echo $this->element('preserve/addphotoform',['type'=>'mammal','id'=>$mammal['id'],'pcount'=>$pcount,'col'=>$col]); ?>
</div>