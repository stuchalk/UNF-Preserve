<?php
// Element: Shows debug window
// File: debug.ctp
// Variables: None
// v1.0 SJC 11/24/12
?>
<!-- element: debug.ctp -->
<div class="debugbar" onclick="togglevis('debug');">&nbsp;</div>
<div id="debug" class="debug" style="display: none;"><?php pr($data); ?></div>