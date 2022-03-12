<?php
echo "<h3>Upload file</h3>";
echo $this->Form->create('Repository',array('action'=>'ingest','enctype' => 'multipart/form-data'));
echo $this->Form->input('file',array('type'=>'file'));
echo $this->Form->end('Ingest');
?>