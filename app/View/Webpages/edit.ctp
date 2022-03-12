<script>
	tinymce.init({
		selector: "textarea#WebpageContent",
		theme: "modern",
		height: 500,
		plugins: [
			["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker"],
			["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
			["save table contextmenu directionality emoticons template paste"]
		],
		add_unload_trigger: false,
		schema: "html5",
		toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		statusbar: false
	});
</script>

<div class="left">
	<?php echo $this->element('preserve/layout'); ?>
</div>
<div class="right">
	<h2>Edit the page...</h2>
	<?php echo $this->Form->create('Webpage',array('action'=>'update/'.$loc)); ?>
	<div id='page' style='border: 1px solid grey;box-shadow: 5px 5px 3px #888888;padding: 10px;margin: 10px 0px 10px 0px;'>
		<h3>Title</h3>
		<?php echo $this->Form->input('title',array('type'=>'text','div'=>false,'label'=>false,'size'=>50,'value'=>$data['title'])); ?>
		<h3>Content</h3>
		<?php echo $this->Form->input('content',array('type'=>'textarea','div'=>false,'label'=>false,'value'=>$data['content'])); ?>
	</div>
	<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$args['id'])); ?>
	<?php echo $this->Form->end('Save Changes'); ?>
	<?php //pr($data); ?>
</div>