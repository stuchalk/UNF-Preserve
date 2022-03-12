<?php
// Element: Show a list of datastreams of an object
// File: objstrs.ctp
// Variables: $data
// v1.0 SJC 12/17/12
?>
<!-- element: objstrs.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('objstrs');">Datastreams</div>
		<div class="clear"></div>
	</div>
	<div id="objstrs" class="leftdivbody">
		<?php
			$streams=$this->requestAction('/datastreams/listall/'.$args['pid']);
			echo "<ul>";
			foreach($streams as $str=>$title)
			{
				// Check if stream is A, I, or D?
				$meta=$this->requestAction('/datastreams/metadata/'.$args['pid'].'/'.$str);
				if($meta['dsState']=='A'):
					echo "<li>".$this->Html->link($title,'/datastreams/view/'.$args['pid'].'/'.$str)."</li>";
				elseif($meta['dsState']=='I'):
					echo "<li>".$title." (Inactive) ".$this->Html->link('Activate','/datastreams/undelete/'.$args['pid'].'/'.$str)."</li>";
				elseif($meta['dsState']=='D'):
					echo "<li>".$title." (Deleted) ".$this->Html->link('Activate','/datastreams/undelete/'.$args['pid'].'/'.$str)."</li>";
				endif;
			}
			echo "</ul>";
		?>
	</div>
</div>