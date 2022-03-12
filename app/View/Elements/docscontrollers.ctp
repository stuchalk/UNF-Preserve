<?php
// Element: Show list of links to controller documentation
// File: docscontrollers.ctp
// Variables: $controller
// v1.0 SJC 12/17/12
?>
<!-- element: docscontrollers.ctp -->
<h3>Controllers</h3>
<ul>
	<?php if($this->action!="datastreams")		{ echo "<li>".$this->Html->link('Datastreams','/docs/datastreams')."</li>"; } ?>
	<?php if($this->action!="methods")			{ echo "<li>".$this->Html->link('Methods','/docs/methods')."</li>"; } ?>
	<?php if($this->action!="objects")			{ echo "<li>".$this->Html->link('Objects','/docs/objects')."</li>"; } ?>
	<?php if($this->action!="relationships")	{ echo "<li>".$this->Html->link('Relationships','/docs/relationships')."</li>"; } ?>
	<?php if($this->action!="repository")		{ echo "<li>".$this->Html->link('Repository','/docs/repository')."</li>"; } ?>
	<?php if($this->action!="services")			{ echo "<li>".$this->Html->link('Services','/docs/services')."</li>"; } ?>
</ul>
