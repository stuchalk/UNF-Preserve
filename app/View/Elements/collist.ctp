<?php
// Element: Display athe list of collections under this namespace
// File: collist.ctp
// Variables: $vis
// v1.0 SJC 11/24/12
if(!isset($vis)) { $vis=true; }
?>
<!-- element: collist.ctp -->
<div class="leftdiv">
	<div class="leftdivheader">
		<div class="leftdivtitle" onclick="togglevis('collist');">Collections</div>
		<div class="clear"></div>
	</div>
	<div id="collist" class="leftdivbody" style="<?php echo ($vis==true? 'display: block;':'display: none;'); ?>">
		<ul>
		<?php
			$cols=$this->requestAction('/collections/index');
			foreach($cols as $pid=>$title)
			{
				echo "<li>".$this->Html->link($title,'/collections/view/'.$pid);
				$subcols=$this->requestAction('/collections/subcols/'.$pid);
				if(!empty($subcols['subcols']))
				{
					echo "<ul>";
					foreach($subcols['subcols'] as $pid=>$title)
					{
						echo "<li id='small'>".$this->Html->link($title,'/collections/view/'.$pid)."</li>";
					}
					echo "</ul>";
				}
				echo "</li>";
			}
		?>
		</ul>
	</div>
</div>