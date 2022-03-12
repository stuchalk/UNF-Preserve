<?php
// Element: Display an XML file in a webpage (not downloaded into browser)
// File: viewmarkup.ctp
// Variables: $data
// v1.0 SJC 12/17/12
?>
<!-- element: viewmarkup.ctp -->
<?php
if(stristr($data,'</math')):		echo "<p class='large'>".$data."</p>";
elseif(stristr($data,'</html')):	echo '<pre>'.htmlentities($data).'</pre>';
else:								$from=array(" xmlns"," xsi"," http://","><");
									$to=array("\r  xmlns","\r  xsi","\r  http://",">\r  <");
									echo '<pre>'.htmlentities(str_replace($from,$to,$data)).'</pre>';
endif;
?>