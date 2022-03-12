<?php
if($args['col']!="")
{
	include('http://localhost:8080/fedora/objects/'.$args['col'].'/datastreams/FORM/content');
}
else
{
?>
<div class="left">
	What goes over here?
</div>
<div class="right">
	<h2>Add a New Item to the Library</h2>
	<?php echo $this->Form->create('Item',array('action'=>'add','enctype'=>'multipart/form-data')); ?>
	<table width="700">
		<tr>
			<td align="right" width="100" valign="top" style="text-align: right;">*PID:</td>
			<td valign="top"><?php echo $this->Form->input('pid',array('type'=>'text','label'=>false,'div'=>false,'size'=>'10')); ?></td>
		</tr>
		<tr>
			<td width="100" class="textright">*Collection:</td>
			<td width="600" ><?php echo $this->Form->input('collection',array('type'=>'select','label'=>false,'selected'=>'empty','options'=>array(''=>'Choose...')+$data,'onchange'=>'getSelectOptions("/collections/subcols/" + this.options[this.selectedIndex].value,"ItemSubcols");return false;','div'=>false,'align'=>'top')); ?><br>
			<?php echo $this->Form->input('subcols',array('type'=>'select','label'=>false,'selected'=>'empty','options'=>array(),'div'=>false,'multiple'=>true,'size'=>'5','align'=>'top','style'=>"display: none;")); ?></td>
		</tr>
		<tr>
			<td class="textright">*Item Title:</td>
			<td><?php echo $this->Form->input('Item.dc.title',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50')); ?></td>
		</tr>
		<tr>
			<td class="textright">*Description:</td>
			<td><?php echo $this->Form->input('Item.dc.description',array('type'=>'textarea','label'=>false,'div'=>false,'rows'=>'2','cols'=>'50')); ?></td>
		</tr>
		<tr>
			<td class="textright">*Author(s):</td>
			<td>
				<input id="creatorIndex" type="hidden" value="0"/>
				<div id='creators' style="margin-left: 6px;">
				</div>
				<?php echo $this->Form->input('Item.dc.creatorText',array('type'=>'text','class'=>'small','label'=>false,'div'=>false,'size'=>'50','autocomplete'=>'off','onkeyup'=>"livesearch('/objects/rsearch','creator',this.value,'[Item][dc]','ItemDcCreatorText');return false;")); ?> 
				<?php echo $this->Form->button('Add Author',array('type'=>'button','label'=>false,'div'=>false,'id'=>'creatorAdd','onclick'=>"addItem('creator',document.getElementById('ItemDcCreatorText').value,'[Item][dc]','ItemDcCreatorText');return false;")); ?>
			</td>
		</tr>
		<tr>
			<td class="textright">Date:</td>
			<td><?php echo $this->Form->input('Item.dc.created',array('type'=>'date','dateFormat'=>'YMD','empty'=>false,'maxYear'=>2013,'minYear'=>1900,'label'=>false,'div'=>false)); ?></td>
		</tr>
		<tr>
			<td class="textright">URL:</td>
			<td><?php echo $this->Form->input('Item.dc.source',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50')); ?></td>
		</tr>
		<tr>
			<td class="textright">Subject(s):</td>
			<td>
				<input id="subjectIndex" type="hidden" value="0"/>
				<div id='subjects' style="margin-left: 6px;">
				</div>
				<?php echo $this->Form->input('Item.dc.subjectText',array('type'=>'text','class'=>'small','label'=>false,'div'=>false,'size'=>'50','autocomplete'=>'off','onkeyup'=>"livesearch('/objects/rsearch','subject',this.value,'[Item][dc]','ItemDcSubjectText');return false;")); ?> 
				<?php echo $this->Form->button('Add Subject',array('type'=>'button','label'=>false,'div'=>false,'id'=>'subjectAdd','onclick'=>"addItem('subject',document.getElementById('ItemDcSubjectText').value,'[Item][dc]','ItemDcSubjectText');return false;")); ?>
			</td>
		</tr>
		<tr>
			<td class="textright">Citation:</td>
			<td><?php echo $this->Form->input('Item.dc.citation',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50')); ?></td>
		</tr>
		<tr>
			<td class="textright">Publisher:</td>
			<td>
				<input id="publisherIndex" type="hidden" value="0"/>
				<div id='publishers' style="margin-left: 6px;">
				</div>
				<?php echo $this->Form->input('Item.dc.publisherText',array('type'=>'text','class'=>'small','label'=>false,'div'=>false,'size'=>'50','autocomplete'=>'off','onkeyup'=>"livesearch('/objects/rsearch','publisher',this.value,'[Item][dc]','ItemDcPublisherText');return false;")); ?> 
				<?php echo $this->Form->button('Add Publisher',array('type'=>'button','label'=>false,'div'=>false,'id'=>'publisherAdd','onclick'=>"addItem('publisher',document.getElementById('ItemDcPublisherText').value,'[Item][dc]','ItemDcPublisherText');return false;")); ?>
			</td>
		</tr>
		<tr>
			<td class="textright">Resource Type:</td>
			<td>
			<?php
				$options=array(''=>'Choose...','Book'=>'Book','Book Chapter'=>'Book Chapter','Database'=>'Database','Dataset'=>'Dataset','Dissertation'=>'Dissertation/Thesis','GIS'=>'GIS Data','Guide'=>'Guide','Journal Article'=>'Journal Article','Law'=>'Law','Map'=>'Map','Press Release'=>'Press Release','Report'=>'Report','Website'=>'Website','Workshop'=>'Workshop');
				echo $this->Form->input('Item.dc.type',array('type'=>'select','label'=>false,'div'=>false,'options'=>$options));
			?>
			</td>
		</tr>
		<tr>
			<td class="textright">Google Books:</td>
			<td><?php echo $this->Form->input('Item.dc.relation',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50')); ?></td>
		</tr>
		<tr>
			<td class="textright">File:</td>
			<td><?php echo $this->Form->input('upload',array('type'=>'file','label'=>false,'div'=>false)); ?></td>
		</tr>
	</table>
	<p><?php echo $this->Form->end('Add Item'); ?></p>
</div>
<?php } ?>