<div class="left">
	<h2>Repository Info</h2>
</div>
<div class="right">
	<h2><?php echo $data['repositoryName']; ?></h2>
	<div class="righttextbox">
		<?php
		echo "<p>Version: ".$data['repositoryVersion']."</p>";
		if(isset($data['AdminEmail']))
		{
			echo "<p>Contacts: ";$temp="";
			foreach($data['AdminEmail'] as $admin)
			{
				$temp.=$this->Html->link($admin,"mailto:".$admin).", ";
			}
			echo substr($temp,0,-2)."</p>";
		}
		elseif(isset($data['adminEmail']))
		{
			echo "<p>Contact: ".$this->Html->link($data['adminEmail'],"mailto:".$data['adminEmail'])."</p>";
		}
		?>
	</div>
	<?php echo $this->element('repostats'); ?>
	<?php echo $this->element('viewvars'); ?>
</div>