<?php
// Element: Show stats on the top hits based on rsearch
// File: rsearchtoplist.ctp
// Variables: $title (required), $sfield (required), $limit (optional), $sort(optional)
// Adapts to the controller calling it through $controller
// v1.0 SJC 12/17/12
($this->name=="Pages") ? $controller="objects" : $controller=lcfirst($this->name);
?>
<!-- element: rsearchtoplist.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" style="cursor: auto;"><?php echo $title; ?></div>
		<div class="clear"></div>
	</div>
	<div class="leftdivbody">
		<ul>
		<?php
			if(!isset($sort)) { $sort="ASC_T"; }
			if(!isset($limit)) { $limit=0; }
			if(!isset($output)) { $output='count'; }
			$stats=$this->requestAction('/items/rsearch/'.$sfield.'/*/all/'.$sort.'/'.$limit.'/0/'.$output);
			if($output=="count")
			{
				foreach($stats as $term=>$count)
				{
					echo "<li>".$this->Html->link($term." (".$count.")",'/'.$controller.'/rsearch/'.$sfield.'/'.$term.'')."</li>";
				}
			}
			else
			{
				foreach($stats as $obj)
				{
					echo "<li>".$this->Html->link($obj['title'],'/'.$controller.'/view/'.$obj['pid'])."</li>";
				}
			}
		?>
		</ul>
	</div>
</div>