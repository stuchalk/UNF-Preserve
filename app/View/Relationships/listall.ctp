<div class="left">
	<h2>List Relationships</h2>
</div>
<div class="right">
	<h3>Description</h3>
	<p>Get a list of the relationships of an object</p>
	<h3>CakePHP URL Format</h3>
	<p>/relationships/listall/{pid}/{output} ? [subject] [predicate] [format]</p>
	<p>(NOTE: Fedora GET variables can be sent as either GET or POST)</p>
	<h3>CakePHP REST Variables</h3>
	<ul>
		<li>pid: PID of digital object</li>
		<li>output: How/where to return content [array|xml]</li>
	</ul>
	<h3>Fedora GET/POST Variables</h3>
	<ul>
		<li>[subject] is the pid of the object and/or plus its dsid</li>
		<li>[predicate] is the type of relationship between the subject and the object</li>
		<li>([format] is not needed as hardcoded in fedora_source as xml)</li>
	</ul>
	<h3>Output sent to CakePHP view</h3>
	<ul>
		<li>ARRAY/XML $data (relationship predicates and objects of the subject (the $pid))</li>
		<div class="righttextbox"><?php echo $this->element('viewdata',array('data'=>$data)); ?></div>
		<li>ARRAY $args (CakePHP REST variables)</li>
		<div class="righttextbox"><?php pr($args); ?></div>
	</ul>
	<h3>Fedora REST API Equivalent</h4>
	<p>listRelationships: GET /objects/{pid}/relationships ? [subject] [predicate] [format]</p>
</div>