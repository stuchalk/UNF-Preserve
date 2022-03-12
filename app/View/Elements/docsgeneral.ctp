<?php
// Element: Show list of links to general documentation
// File: docscontrollers.ctp
// Variables: $controller
// v1.0 SJC 12/17/12
(isset($this->params['pass'][0])) ? $file=$this->params['pass'][0] : $file="";
?>
<!-- element: docsgeneral.ctp -->
<h3>General</h3>
<ul>
	<?php if($file!="")				{ echo "<li>".$this->Html->link('Documentation Home','/docs')."</li>"; } ?>
	<?php if($file!="overview")		{ echo "<li>".$this->Html->link('Overview','/docs/index/overview')."</li>"; } ?>
	<?php if($file!="files")		{ echo "<li>".$this->Html->link('Important Files','/docs/index/files')."</li>"; } ?>
	<?php if($file!="technical")	{ echo "<li>".$this->Html->link('Technical Info','/docs/index/technical')."</li>"; } ?>
</ul>
