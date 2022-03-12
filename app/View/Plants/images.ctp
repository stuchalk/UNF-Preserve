<?php
	// Data setup - $data and $args coming from controller
	$plant=$data['Plant'];
    //pr($plant);exit;
	list($ns,$id)=explode(":",$plant['url']);
	$website=$this->requestAction('/websites/view/'.$ns);
	$site=$website['Website'];
?>

<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<!-- Display example photo if available -->
	<?php echo $this->element('preserve/stockphoto',['species'=>$plant,'site'=>$site]); ?>
	
	<!-- Display species data -->
	<div id="topright" style="float: left;">
		<h2>Plant Inventory</h2>
		<h3><i><?php echo $plant['sname']; ?></i></h3>
		<p><b>Common Name:</b> <?php echo $plant['cname']; ?><br />
		<b>Group:</b> <?php echo $plant['group']; ?><br />
		<b>Family:</b> <?php echo $plant['family']; ?><br />
		<b>Last Updated:</b> <?php echo date("F j, Y, g:i a",strtotime($plant['updated'])); ?>
		<?php if($plant['comment']!="") { echo "<br /><b>Comments:</b> ".$plant['comment']; } ?>
		</p>
		<?php
			if($plant['url']!="")
			{
				list($ns,$id)=explode(":",$plant['url']);
				echo "<p>More information on this species can be found ".$this->Html->link('here',$site['url'].$id,['target'=>'_blank'])."</p>";
			}
		?>
		<p>Set this species as protected/open</p>
		<?php
			$script="setProtection('".$plant['id']."',this.value)";
			echo "<div>".$this->Form->input('protected',['type'=>'radio','value'=>$plant['protected'],'div'=>false,'options'=>['0'=>'Open','1'=>'Protected'],'legend'=>false,'onchange'=>$script])."</div>";
		?>
	</div>
	<div class="clear"></div>

	<p>&nbsp;</p>
	<h3>Existing Photographs</h3>
	<?php
	if(!empty($photos))
	{
        $pcount=count($photos);
		$type="plant";$name=$plant['cname'];
		foreach($photos as $photo) { echo $this->element('preserve/unfphoto',['divid'=>'photo'.$photo['pid'],'type'=>$type,'name'=>$name,'photo'=>$photo]); }
		echo "<div class='clear'></div>";
	}
	else
	{
		$pcount=0;
		echo "<i>There are currently no photographs for this plant - add some below!</i>";
	}
	?>
	<div class='clear'></div>
	<p>&nbsp;</p>
	<?php echo $this->element('preserve/addphotoform',['type'=>'Plant','id'=>$plant['id'],'pcount'=>$pcount,'col'=>$col]); ?>
</div>