<?php $dc=$data['DC']['content']['Dc']; ?>
<div class="left">
	<h2>Collection Item</h2>
	<?php if(isset($data['streams']['KML'])) { echo $this->element('objloc'); } ?>
	<?php if(isset($data['streams']['THUMB'])&&!stristr($dc['format'],'image')) { echo $this->element('objthumb'); } ?>
	<?php echo $this->element('objmeta'); ?>
	<?php if(isset($data['streams']['EXIF'])) { echo $this->element('objexif'); } ?>
	<?php echo $this->element('objcols'); ?>
	<?php echo $this->element('objadmin'); ?>
	<?php if(isset($data['streams']['EXIF'])&&isset($data['streams']['KML'])) { echo $this->element('weather'); } ?>
</div>
<div class="right">
	<h2><?php echo $data['objLabel']; ?></h2>
	<?php
	// View DC
	if(isset($dc['description']))
	{
		echo "<h3>Description</h3><p align=\"justify\">".$dc['description']."</p>";
	}
	if(isset($dc['creator']))
	{
		echo "<h3>Author</h3><p>".$this->Html->link($dc['creator'],'/objects/rsearch/creator/'.$dc['creator'],array('title'=>'Click to search for this author'))."</p>";
	}
	elseif(isset($dc['Creator']))
	{
		echo "<h3>Authors</h3><p>";
		$temp="";
		foreach($dc['Creator'] as $author)
		{
			$temp.=$this->Html->link($author,'/objects/rsearch/creator/'.$author,array('title'=>'Click to search for this author')).", ";
		}
		echo substr($temp,0,-2)."</p>";
	}
	if(isset($dc['publisher']))
	{
		echo "<h3>Publisher</h3><p>".$this->Html->link($dc['publisher'],'/objects/rsearch/publisher/'.$dc['publisher'],array('title'=>'Click to search for this publisher'))."</p>";
	}
	if(isset($dc['type']))
	{
		echo "<h3>Type</h3><p>".$this->Html->link($dc['type'],'/objects/rsearch/type/'.$dc['type'],array('title'=>'Click to search for this type'))."</p>";
	}
	if(isset($dc['date']))
	{
		echo "<h3>Date</h3><p>".date("F j, Y",strtotime($dc['date']))."</p>";
	}
	if(isset($dc['contributor']))
	{
		echo "<h3>Citation</h3><p><i>".$dc['contributor']."</i></p>"; // Contributor used for citation instead of dcterms:bibliographicCitation as its part of the oai_dc specification
	}
	if(isset($dc['subject']))
	{
		echo "<h3>Subject</h3><p>".$this->Html->link($dc['subject'],'/objects/rsearch/subject/'.$dc['subject'],array('title'=>'Click to search for this term'))."</p>";
	}
	elseif(isset($dc['Subject']))
	{
		echo "<h3>Subjects</h3><p>";
		$temp="";
		foreach($dc['Subject'] as $subject)
		{
			$temp.=$this->Html->link($subject,'/objects/rsearch/subject/'.$subject,array('title'=>'Click to search using this term'))." • ";
		}
		echo substr($temp,0,-4)."</p>";  // 4 chars as • is a 3 byte char
	}
	if(isset($dc['source']))
	{
		echo "<h3>Online Source</h3><p>".$this->Html->link($dc['source'],null,array('target'=>"_blank"))." ";
		if(isset($dc['relation'])) { ?>
		<a href="javascript:void(0)" title="Click to show"
			onclick="Modalbox.show('<?php echo Configure::read('jaf.path'); ?>/utils/gbook/<?php echo $data['pid']; ?>', { title: 'Google Books', width: 710 }); return false;">
			(View in Google Books)
		</a>
		<?php }
		echo "</p>";
	}
	if(isset($dc['isPartOf']))
	{
		echo "<h3>Part Of</h3><p>".$dc['isPartOf']."</p>";
	}
	if(isset($data['CONTENT']))
	{
		echo "<h3>Download from the Library</h3>";
		if(isset($data['CONTENT']['icon']))
		{
			echo $this->Html->link($this->Html->image($data['CONTENT']['icon'],array("alt"=>"PDF","height"=>"50")),$data['CONTENT']['exturl'],array('escape' => false));
		}
		else
		{
			echo $this->Html->link($this->Html->image($data['CONTENT']['exturl'],array("alt"=>"Image")),$data['CONTENT']['exturl'],array('escape' => false));
		}
	}
	$mainstr=Configure::read('fed.item.stream');
	if(isset($data[$mainstr]))
	{
		echo "<h3>Image (Click to download)</h3>";
		// Make url to add watermark
		$makrurl='http://ecenter.unf.edu';
		echo $this->Html->link($this->Html->image($data[$mainstr]['content']['exturl'],array("alt"=>"Image",'width'=>"100%")),$data[$mainstr]['content']['exturl'],array('escape' => false));
	}
	if(isset($data['SNAPSHOT']))
	{
		$snap=$this->requestAction('/datastreams/metadata/'.$args['pid'].'/SNAPSHOT');
		echo "<h3>Download Snapshot of Website from ".date('F j, Y',strtotime($snap['dsCreateDate']))."</h3>";
		echo $this->Html->link($this->Html->image("http://".Configure::read('jaf.path')."/".$data['SNAPSHOT']['icon'],array("alt"=>"PDF","height"=>"50")),$data['SNAPSHOT']['exturl'],array('escape' => false));
	}
	if(isset($data['DOI']))
	{
		echo "<h3>DOI</h3><p>".$this->Html->link($data['DOI']['content'],null,array('target'=>"_blank"))."</p>";
	}
	if(isset($dc['rights']))
	{
		echo "<p><i>".$dc['rights']."</i></p>";
	}
	?>
</div>