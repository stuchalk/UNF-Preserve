<div class="left">
	<h2>RI Search</h2>
</div>
<div class="right">
	<h3>Description</h3>
	<p>Get a list of the relationships that match a defined pattern</p>
	<h3>CakePHP URL Format</h3>
	<p>/services/risearch/{query}/{type}/{output} ? [template] ... [lang] [flush] [distinct] [stream] [limit]</p>
	<p>(NOTE: RI search GET variables can be sent as either GET or POST - POST avoids encoding issues)</p>
	<h3>CakePHP REST Variables (Fedora required variables)</h3>
	<ul>
		<li>query: Search string.  If you want to limit the search to objects in a particular namespace you must include a filter<br />(e.g. for sparql: select $s $p $o where { $s $p $o. filter regex(str($o),'pidns') })<br />[don't use '?' for variables as won't pass through Cakephp URLs]</li>
		<li>type: The search type - tuples OR triples</li>
		<li>output: How to return content (also used to define risearch [format]) [array] OR [CSV|Simple|Sparql|TSV] OR [N-Triples|Notation 3|RDF/XML|Turtle|count]</li>
	</ul>
	<h3>Fedora GET/POST Optional Variables</h3>
	<ul>
		<li>[template] is a template output format for triple itql/sparql queries</li>
		<li>([lang] not needed as automatically detected from the query string)</li>
		<li>([flush] [distinct] [stream] [limit] are not needed as hardcoded in fedora_source)</li>
	</ul>
	<h3>Output sent to CakePHP view</h3>
	<ul>
		<li>ARRAY/XML $data (relationship predicates and objects of the subject (the $pid))</li>
		<div class="righttextbox"><?php echo $this->element('viewdata',array('data'=>$data)); ?></div>
		<li>ARRAY $args (CakePHP REST variables)</li>
		<div class="righttextbox"><?php pr($args); ?></div>
	</ul>
	<h3>Fedora RI Search Equivalent</h4>
	<p>risearch: GET/POST /fedora/risearch ? [type] [flush] [lang] [format] [limit] [distinct] [stream] [query] [template]</p>
</div>