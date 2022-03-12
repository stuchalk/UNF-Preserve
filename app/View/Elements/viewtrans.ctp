<?php
// Element: Display output of XSL transform
// File: viewtrans.ctp
// Variables: $pid, $dsid, $style
// v1.0 SJC 10/07/12
?>
<!-- element: viewtrans.ctp -->
<?php echo $this->requestAction('/services/saxon/'.$pid.'*'.$dsid.'/'.$style,array('return')); ?>