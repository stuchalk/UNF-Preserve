<?php
// Element: Display form to upload a Fedora Object in XML
// File: objingest.ctp
// Variables: $data, $vis
// v1.0 SJC 12/17/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: objingest.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('objingest');">Ingest New Object</div>
		<div class="clear"></div>
	</div>
	<div id="objingest" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<?php
		echo $this->Form->create('Repository',array('enctype'=>'multipart/form-data','action'=>'ingest'));
		echo $this->Form->input('file',array('type'=>'file','size'=>'15','label'=>false,'div'=>false));
		echo $this->Form->end('Ingest');
		?>
	</div>
</div>