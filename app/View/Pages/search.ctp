<div class="left">
	<h2>Repository Search</h2>
	<p class="left">This pages allows users to search the repository using each of the three methods available. 
		The 'General Search' is done using the Fedora REST API findobjects call, 
		the 'Field Search' is performed using a Fedora Resource Index Search, 
		and the 'Fulltext Search' uses the Fedora GSearch REST interface.</p>
	<div class="leftspacer"></div>
	<?php echo $this->element('rsearchtoplist',array('title'=>'By Title (Newest 10)','sfield'=>'lastModifiedDate','limit'=>'10','sort'=>'DESC_F','output'=>'array')); ?>
</div>
<div class="right">
	<p class="browse">There are many different ways to search the library.  To your left are pre-described searches based on library statistics for content type, year, and ?.
	Below are free form searches of all search fields, specific search fields, full text searching of the content of PDF articles (may produce a "Google" response :) ).</p>
	<div class="righttextbox">
		<h3>General Search</h3>
		<p>
			<?php
			echo $this->Form->create('Object',array('action'=>'fsearch'));
			echo $this->Form->input('output',array('type'=>'hidden','value'=>'detail'));
			echo $this->Form->input('terms',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50'));
			echo $this->Form->end('Search');
			?>
		</p>
	</div>
	<div class="righttextbox">
		<h3>Field Search</h3>
		<p>
		<?php
			echo $this->Form->create('Object',array('action'=>'rsearch'));
			$options=Configure::read('risearch.preds.dc');
			echo $this->Form->input('place',array('type'=>'select','label'=>false,'div'=>false,'options'=>array('any'=>'Anywhere in','start'=>'Start of','all'=>'Match')));
			echo $this->Form->input('field',array('type'=>'select','label'=>false,'div'=>false,'options'=>array(''=>'Choose...')+$options));
			echo $this->Form->input('value',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50'));
			echo $this->Form->end('Search Field');
		?>
		</p>
	</div>
	<div class="righttextbox">
		<h3>Fulltext Search</h3>
		<p>
			<?php
			echo $this->Form->create('Service',array('action'=>'gsearch'));
			echo $this->Form->input('field',array('type'=>'hidden','value'=>'any'));
			echo $this->Form->input('value',array('type'=>'text','label'=>false,'div'=>false,'size'=>'50'));
			echo $this->Form->end('Search Fulltext');
			?>
		</p>
	</div>
</div>