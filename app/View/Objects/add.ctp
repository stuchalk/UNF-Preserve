<div class="left">
	<h2>Add a New Object</h2>
	<p class="browse">Enter object and dublin core in the fields provided.  To add content and relationships click the 'Add' button to the right of 'Datastreams' and Relationships and fill in the details.  You may add as many datastreams and relationhships as you need</p>
</div>
<div class="right">
	<?php echo $this->Form->create('Object',array('action'=>'add','enctype' => 'multipart/form-data'));?>
	<h3>Object Metadata</h3>
	<div class="righttextbox">
		<table width="100%">
			<tr>
				<td width="70" class="textright">PID: </td>
				<td><?php echo $this->Form->input('Object.meta.pid',array('label'=>false,'size'=>10,'value'=>Configure::read('jaf.pidns').":",'onkeyup'=>'checkpid(this.id);')); ?><div id="temp"></div></td>
			</tr>
			<tr>
				<td class="textright">Label: </td>
				<td><?php echo $this->Form->input('Object.meta.label',array('label'=>false,'size'=>50)); ?></td>
			</tr>
			<tr>
				<td class="textright">OwnerId: </td>
				<td><?php echo $this->Form->input('Object.meta.ownerId',array('label'=>false,'size'=>40)); ?></td>
			</tr>
			<tr>
				<td class="textright">Message: </td>
				<td><?php echo $this->Form->input('Object.meta.logMessage',array('label'=>false,'size'=>60)); ?></td>
			</tr>
		</table>
	</div>
	<h3>DC Metadata</h3>
	<div class="righttextbox">
		<table width="100%">
			<tr>
				<td width="70" class="textright">Title: </td>
				<td><?php echo $this->Form->input('Object.dc.title',array('label'=>false,'size'=>50)); ?></td>
			</tr>
			<tr>
				<td class="textright">Author: </td>
				<td><?php echo $this->Form->input('Object.dc.author',array('label'=>false,'size'=>40)); ?></td>
			</tr>
			<tr>
				<td class="textright">Description: </td>
				<td><?php echo $this->Form->input('Object.dc.description',array('label'=>false,'size'=>30)); ?></td>
			</tr>
			<tr>
				<td class="textright">Created: </td>
				<td><?php echo $this->Form->dateTime('Object.dc.created','MDY', 12, time(), array('maxYear'=>'2013','minYear'=>'1970')); ?></td>
			</tr>
		</table>
	</div>
	<h3 id="strs">Datastreams <span class="doajax" onclick="addCakeElement('strs','/utils/ajax/strform/','strsForm');">Add</span></h3>
	<h3 id="rels">Relationships <span class="doajax" onclick="addCakeElement('rels','/utils/ajax/relform/','relsForm');">Add</span></h3>
	<?php echo '<p>&nbsp;</p>'.$this->Form->end('Add Object'); ?>
	<form id="strsForm">
		<input type="hidden" name="prestr" value="Object.streams."/>
		<input type="hidden" name="count" value="0">
	</form>
	<form id="relsForm">
		<input type="hidden" name="prestr" value="Object.rels."/>
		<?php
			// Relationships
			// Predicates
			foreach(Configure::read('jaf.preds') as $key=>$rel)		{ $temp[$key]='jaf: '.$rel; }
			foreach(Configure::read('fed.relsext') as $key=>$rel)	{ $temp[$key]='relsext: '.$rel; }
			$preds=array(''=>'Choose Predicate...')+$temp;
			foreach($preds as $key=>$value)
			{
			?>
			<input type="hidden" name="preds[<?php echo $key; ?>]" value="<?php echo $value; ?>">
			<?php
			}
			// Objects
			$temp=$this->requestAction('/objects/fsearch/'.Configure::read('jaf.pidns').'*');
			$objs=array(''=>'Choose Object...')+$temp['results'];  //  $objs[] hidden field created for no selection means the key of the array is 0.  Filtered rels->add
			foreach($objs as $pid=>$title)
			{
			?>
			<input type="hidden" name="objs[<?php echo $pid; ?>]" value="<?php echo substr($title,0,40).'...'; ?>">
			<?php
			}
		?>
		<input type="hidden" name="count" value="0">
	</form>
</div>