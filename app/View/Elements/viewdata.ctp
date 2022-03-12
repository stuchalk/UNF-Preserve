<?php
// Element: Display data in view (either as array or xml (generic output of Fedora))
// File: viewdata.ctp
// Variables: $data (from view)
// v1.0 SJC 12/17/12
?>
<!-- element: viewdata.ctp -->
<?php
if(is_array($data)):	echo pr($data);
else:					echo $this->element('viewmarkup',array('data'=>$data));
endif;
?>