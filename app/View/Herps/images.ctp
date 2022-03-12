<?php
	// Data setup - $data and $args coming from controller
	$herp=$data['Herp'];
	list($ns,$id)=explode(":",$herp['url']);
	$website=$this->requestAction('/websites/view/'.$ns);
	$site=$website['Website'];
?>
<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<!-- Display example photo if available -->
	<?php echo $this->element('preserve/stockphoto',['species'=>$herp,'site'=>$site]); ?>
	
	<!-- Display species data -->
	<div id="topright" style="float: left;">
	<h2>Amphibian/Reptile Inventory</h2>
		<h3><i><?php echo $herp['sname']; ?></i></h3>
		<p><b>Common Name:</b> <?php echo $herp['cname']; ?><br />
		<b>Order:</b> <?php echo $herp['order']; ?><br />
		<b>Family:</b> <?php echo $herp['family']; ?><br />
		<b>Last Updated:</b> <?php echo date("F j, Y, g:i a",strtotime($herp['updated'])); ?>
		<?php if($herp['comment']!="") { echo "<br /><b>Comments:</b> ".$herp['comment']; } ?>
		<?php echo "</p>"; ?>
		<?php
			if($herp['url']!="")
			{
				list($ns,$id)=explode(":",$herp['url']);
				echo "<p>More information on this species can be found ".$this->Html->link('here',$site['url'].$id,['target'=>'_blank'])."</p>";
			}
		?>
		<p>Set this species as protected/open</p>
		<?php
			$script="setProtection('".$herp['id']."',this.value)";
			echo "<div>".$this->Form->input('protected',['type'=>'radio','value'=>$herp['protected'],'div'=>false,'options'=>['0'=>'Open','1'=>'Protected'],'legend'=>false,'onchange'=>$script])."</div>";
		?>
	</div>
	<div class="clear"></div>
		
	<p>&nbsp;</p>
	<h3>Existing Photographs</h3>
	<?php
	if(!empty($photos))
	{
        $pcount=count($photos);
        $type="herp";$name=$herp['cname'];
        foreach($photos as $photo) { echo $this->element('preserve/unfphoto',['divid'=>'photo'.$photo['pid'],'type'=>$type,'name'=>$name,'photo'=>$photo]); }
        echo "<div class='clear'></div>";
	}
	else
	{
		$pcount=0;
		echo "<i>There are currently no photographs for this amphibian/reptile - add some below!</i>";
	}
	?>
	<div class='clear'></div>
	<p>&nbsp;</p>
	<?php echo $this->element('preserve/addphotoform',array('type'=>'Herp','id'=>$herp['id'],'pcount'=>$pcount,'col'=>$col)); ?>
</div>