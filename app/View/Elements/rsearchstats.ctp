<?php
// Element: Show a set of search stats based on rsearch
// File: rsearchstats.ctp
// Variables: None
// v1.0 SJC 12/17/12
?>
<!-- element: rsearchstats.ctp -->
<?php
echo $this->element('rsearchtoplist',array('title'=>'By Publication Type','sfield'=>'type'));
echo $this->element('rsearchtoplist',array('title'=>'By Author (Top 5)','sfield'=>'creator','limit'=>'5','sort'=>'DESC_T'));
echo $this->element('rsearchtoplist',array('title'=>'By Subject (Top 5)','sfield'=>'subject','limit'=>'5','sort'=>'DESC_T'));
echo $this->element('rsearchtoplist',array('title'=>'By Publisher (Top 5)','sfield'=>'publisher','limit'=>'5','sort'=>'DESC_T'));
?>
