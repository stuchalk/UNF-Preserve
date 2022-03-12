<div class="left">
	<h2>Collection</h2>
	<p class="browse"><?php echo $this->Html->link('Browse the Collection','/collections/items/'.$args['pid']); ?></p>
	<div class="leftspacer"></div>
	<?php echo $this->element('objmeta'); ?>
	<?php echo $this->element('colstats'); ?>
	<?php echo $this->element('colsubcols'); ?>
	<?php echo $this->element('colhascols'); ?>
	<?php echo $this->element('coladmin'); ?>
</div>
<div class="right">
	<?php
		if(isset($data['THUMB'])&&isset($data['IMAGE'])) { echo $this->element('objthumb',array('float'=>'right','cover'=>'yes')); }
		if(isset($data['THUMB'])&&!isset($data['IMAGE'])) { echo $this->element('objthumb',array('float'=>'right')); }
	?>
	<h2><?php echo $data['profile']['objLabel']; ?></h2>
	<?php
	// DC
	$dc=$data['DC'];pr($dc);
    if(isset($dc['description']))	{ echo "<h3>Description</h3><p align=\"justify\">".$dc['description']."</p>"; }
	if(isset($dc['creator'])&&!is_array($dc['creator']))
	{
		echo "<h3>Author</h3><p>".$dc['creator']."</p>";
	}
	elseif(isset($dc['creator'])&&is_array($dc['creator']))
	{
		echo "<h3>Authors</h3><p>";
		$temp="";
		foreach($dc['creator'] as $author) { $temp.=$author.", "; }
		echo substr($temp,0,-2)."</p>";
	}
	if(isset($dc['subject']))		{ echo "<h3>Subject</h3><p>".$dc['subject']."</p>"; }
	if(isset($dc['source']))		{ echo "<h3>Online Source</h3><p>".$this->Html->link($dc['source'])."</p>"; }
	
	// Map
	if(isset($dc['type'])&&$dc['type']=='geoimageset')
	{
		echo "<h3>Map</h3>";
		if(isset($data['KML']))
		{
			echo $this->element('googlemap',array('lat'=>'30.268','lon'=>'-81.508','type'=>'kml','desc'=>'University of North Florida','points'=>$data['KML']['exturl'].'?dummy='.time()));  // Add dummy var so google does not cache kml file
		}
		else
		{
			echo $this->element('googlemap',array('lat'=>'30.268','lon'=>'-81.508','type'=>'point','desc'=>'University of North Florida','points'=>''));
		}
	}
	
	// Streams
	if(isset($data['CONTENT']))
	{
		echo "<h3>Download from the Library</h3>";
		echo $this->Html->link($this->Html->image(Configure::read('jaf.path').$data['CONTENT']['icon'],array("alt"=>"PDF","height"=>"50")),"http://chalk.coas.unf.edu:8080".$data['CONTENT']['url'],array('escape' => false));
	}

	// Rights
	if(isset($dc['rights']))		{ echo "<p align=\"right\"><i>".$dc['rights']."</i></p>"; }
	?>
</div>