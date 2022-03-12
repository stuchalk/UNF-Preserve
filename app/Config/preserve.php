<?php
// Configuration parameters for the jafFedora site.  Edit as necessary.
// There are addition settings at the end that should not be changed otherwise site functionality will be affected.
// Stuart Chalk 12/31/12

$config['debug']=1;

// jafFedora
$config['jaf']['name']="The UNF Environmental Archive"; // The name of the repository
$config['jaf']['owner']="UNF Environmental Center"; // Name of the owner of the repository
$config['jaf']['pidns']="unfenvc"; // Default namespace of objects in the repository
$config['jaf']['scheme']="https"; // Default protocol for the site [http or https]
$config['jaf']['server']="preserve.unf.edu"; // The name or IP of the server hosting this site
$config['jaf']['site']=""; // A subfolder to the root of the jafFedor site (or "")
$config['jaf']['path']=$config['jaf']['scheme'].'://'.$config['jaf']['server'].$config['jaf']['site']; // Fullpath to the jafFedora site
$config['jaf']['plugins']= [];

// Development
$config['dev']['debug']="no"; // Turns on the debug element in all views (dark blue bar at the bottom of each page - if $data var is present)

// Fedora
$config['fed']['scheme']="http";  // Default protocol for Fedora
$config['fed']['server']="localhost"; // The name or IP of the server hosting Fedora
$config['fed']['port']="8080"; // The http port for Fedora
$config['fed']['sslport']="8443"; // The https port for Fedora (remember to configure Fedora for SSL access)
$config['fed']['context']="fedora"; // Fedora context (defaults to Fedora but can be changed - see Fedora docs)
if($config['fed']['scheme']=="http"):	$config['fed']['path']="http://".$config['fed']['server'].":".$config['fed']['port']."/".$config['fed']['context']; // Local Fedora http path
										$config['fed']['extpath']="http://".$config['jaf']['server'].":".$config['fed']['port']."/".$config['fed']['context']; // External Fedora http path
else:									$config['fed']['path']="https://".$config['fed']['server'].":".$config['fed']['sslport']."/".$config['fed']['context']; // Fedora https path
endif;
$config['fed']['writepath']="/Volumes/data/fedora_files"; // Local directory were Fedora can export files to
$config['fed']['objlabel']="New object added by jafFedora".date("mdy"); // Default object title
$config['fed']['objowner']="LSJRR"; // Default object owner
$config['fed']['objformat']="info:fedora/fedora-system:FOXML-1.1"; // Default object format
$config['fed']['objmessage']['add']="Added using jafFedora"; // Default message when adding an object
$config['fed']['objmessage']['update']="Updated using jafFedora"; // Default message when updating an object
$config['fed']['objmessage']['delete']="Deleted using jafFedora"; // Default message when deleting an object
$config['fed']['manage']['maxsize']=102400000; // Maximum size in bytes that a file can be an 'M' stream.  If greater will be 'E'
$config['fed']['mime2ext']['path']="/Volumes/data/fedora/server/config/mime-to-extensions.xml";  // Path to the mime-to extensions file
$config['fed']['mime2ext']['default']="bin"; // Default file extension (for downlaoded files)
$config['fed']['item']['stream']="Source"; // The ID of the stream that contains the main content for an item
$config['fed']['streams']['protected']= ['PROTECTED']; // Streams that cannot be displayed to anonymous users becuase of copyright
$config['fed']['delete']['object']="hide"; // When deleting an object should it be hidden (state 'Deleted') or purged (actually removed) [hide or purge]
$config['fed']['delete']['stream']="hide"; // When deleting a stream should it be hidden (dsState 'Deleted') or purged (actually removed) [hide or purge]
$config['fed']['findobjects']['fields']['list']= ['pid'=>'true','title'=>'true']; // List of fields to be returned for a 'list' search
$config['fed']['findobjects']['fields']['listdate']= ['pid'=>'true','title'=>'true','date'=>'true']; // List of fields to be returned for a 'listdate' search
$config['fed']['findobjects']['fields']['detail']= ['pid'=>'true','label'=>'true','state'=>'true','ownerId'=>'true','cDate'=>'true','dcmDate'=>'true','title'=>'true','creator'=>'true','subject'=>'true','description'=>'true','contributor'=>'true','date'=>'true','format'=>'true','identifier'=>'true','source'=>'true','type'=>'true','language'=>'true','relation'=>'true','coverage'=>'true','rights'=>'true']; // List of fields to be returned for a 'detail' search

// GSearch
$config['gsearch']['version']='2.5'; // Version of GSearch installed [2.2.4|2.4.2|2.5]
if($config['gsearch']['version']=='2.2.4'):		$config['gsearch']['streamprefix']='dsm'; // GSearch stream prefix in index
elseif($config['gsearch']['version']=='2.4.2'):	$config['gsearch']['streamprefix']='ds';
elseif($config['gsearch']['version']=='2.5'):	$config['gsearch']['streamprefix']='ds';
endif;
$config['gsearch']['indexName']="FgsIndex"; // GSearch index name
$config['gsearch']['fulltext']['streams']= ['CONTENT','PROTECTED','PDF','SNAPSHOT']; // Streams to search fulltext (version<2.5).  In version 2.5 fulltext search is done on

// Other settings (do not change)
// jafFedora
$config['jaf']['preds']['isCollection']="Is Collection";
$config['jaf']['preds']['hasCollectionLevel']="Has Collection Level";
$config['jaf']['preds']['isSubcollection']="Is Subcollection";
$config['jaf']['preds']['isItem']="Is Item";
$config['jaf']['preds']['isTest']="Is Test";
// Fedora
$config['fed']['controlGroup']= ['X','M','R','E'];
$config['fed']['dsState']= ['A','I','D'];
$config['fed']['checksumType']= ['DEFAULT','DISABLED','MD5','SHA-1','SHA-256','SHA-385','SHA-512'];
$config['fed']['export']['formats']['aliases']= ['foxml11','foxml10','mets11','mets10','atom11','atomzip11'];
$config['fed']['export']['formats']['foxml11']="info:fedora/fedora-system:FOXML-1.1";
$config['fed']['export']['formats']['foxml10']="info:fedora/fedora-system:FOXML-1.0";
$config['fed']['export']['formats']['mets11']="info:fedora/fedora-system:METSFedoraExt-1.1";
$config['fed']['export']['formats']['mets10']="info:fedora/fedora-system:METSFedoraExt-1.0";
$config['fed']['export']['formats']['atom11']="info:fedora/fedora-system:ATOM-1.1";
$config['fed']['export']['formats']['atomzip11']="info:fedora/fedora-system:ATOMZip-1.1";
$config['fed']['export']['contexts']= ['public','archive','migrate'];
$config['fed']['relsext']['isPartOf']="Is Part Of";
$config['fed']['relsext']['hasPart']="Has Part";
$config['fed']['relsext']['isConstituentOf']="Is Constituent Of";
$config['fed']['relsext']['hasConstituent']="Has Constituent";
$config['fed']['relsext']['isMemberOf']="Is Member Of";
$config['fed']['relsext']['hasMember']="Has Member";
$config['fed']['relsext']['isSubsetOf']="Is Subset Of";
$config['fed']['relsext']['hasSubset']="Has Subset";
$config['fed']['relsext']['isMemberOfCollection']="Is Member Of Collection";
$config['fed']['relsext']['hasCollectionMember']="Has Collection Member";
$config['fed']['relsext']['isDerivationOf']="Is Derivation Of";
$config['fed']['relsext']['hasDerivation']="Has Derivation";
$config['fed']['relsext']['isDependentOf']="Is Dependent Of";
$config['fed']['relsext']['hasDependent']="Has Dependent";
$config['fed']['relsext']['isDescriptionOf']="Is Description Of";
$config['fed']['relsext']['hasDescription']="Has Description";
$config['fed']['relsext']['isMetadataFor']="Is Metadata For";
$config['fed']['relsext']['hasMetadata']="Has Metadata";
$config['fed']['relsext']['isAnnotationOf']="Is Annotation Of";
$config['fed']['relsext']['hasAnnotation']="Has Annotation";
$config['fed']['relsext']['hasEquivalent']="Has Equivalent";
$config['fed']['formaturi']['text/plain']="http://www.unicode.org/versions/Unicode6.2.0/";
$config['fed']['formaturi']['text/xml']="http://www.w3.org/TR/xml/";
$config['fed']['formaturi']['image/jpeg']="http://www.jpeg.org/jpeg";
$config['fed']['formaturi']['image/jp2']="http://www.jpeg.org/jpeg2000";
$config['fed']['formaturi']['image/png']="http://www.w3.org/TR/PNG";
$config['fed']['formaturi']['image/svg+xml']="http://www.w3.org/TR/SVG11";
$config['fed']['formaturi']['application/pdf']="http://www.adobe.com/devnet/pdf/pdf_reference.html";
$config['fed']['formaturi']['application/msword']="http://www.microsoft.com";
$config['fed']['formaturi']['application/vnd.openxmlformats-officedocument.wordprocessingml.document']="http://www.ecma-international.org/publications/standards/Ecma-376.htm";
$config['fed']['formaturi']['application/vnd.ms-excel']="http://www.microsoft.com";
$config['fed']['formaturi']['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']="http://www.ecma-international.org/publications/standards/Ecma-376.htm";
$config['fed']['formaturi']['application/vnd.ms-powerpoint']="http://www.microsoft.com";
$config['fed']['formaturi']['application/vnd.openxmlformats-officedocument.presentationml.presentation']="http://www.ecma-international.org/publications/standards/Ecma-376.htm";
$config['fed']['formaturi']['chemical/x-mdl-profile']="http://www.symyx.com/downloads/public/ctfile/ctfile.jsp";
$config['fed']['formaturi']['chemical/x-cml']="http://cml.sourceforge.net/schema/";
$config['fed']['formaturi']['chemical/x-jcamp-dx']=$config['formaturi']['dx']="http://www.jcamp-dx.org";
$config['fed']['formaturi']['chemical/x-cif']="http://www.iucr.org/resources/cif/spec";
$config['fed']['formaturi']['image/gif']="http://www.w3.org/Graphics/GIF/spec-gif89a.txt";
$config['fed']['formaturi']['text/html']=$config['formaturi']['htm']="http://www.w3.org/TR/html401/";
$config['fed']['formaturi']['application/x-shockwave-flash']=$config['formaturi']['flv']="http://www.adobe.com/devnet/f4v.html";
$config['fed']['formaturi']['application/rdf+xml']="http://www.w3.org/TR/rdf-schema/";
$config['fed']['formaturi']['application/asp']="http://msdn.microsoft.com/en-us/library/ms526064.aspx";
$config['fed']['formaturi']['application/aspx']="http://www.asp.net";
$config['fed']['formaturi']['text/css']="http://www.w3.org/Style/CSS/Overview.en.html";
$config['fed']['formaturi']['application/js']="http://www.ecma-international.org/publications/standards/Ecma-262.htm";
$config['fed']['formaturi']['application/jsp']="http://www.oracle.com/technetwork/java/jsp-138432.html";
$config['fed']['formaturi']['application/x-httpd-php']="http://www.php.net";
$config['fed']['formaturi']['application/x-httpd-php-source']="http://www.php.net";
$config['fed']['formaturi']['application/rss+xml']="http://web.resource.org/rss/1.0/spec";
$config['fed']['formaturi']['application/xhtml+xml']=$config['formaturi']['text/xhtml']="http://www.w3.org/TR/xhtml11/";
$config['fed']['formaturi']['application/xslt+xml']="http://www.w3.org/TR/xslt20/";
$config['fed']['formaturi']['application/vnd.google-earth.kml+xml']="http://www.opengeospatial.org/standards/kml/";
$config['fed']['formaturi']['application/zip']="http://www.pkware.com/support/zip-app-note/archives";
// RI Search
$config['risearch']['tuples']['format']= ['CSV','Simple','Sparql','TSV'];
$config['risearch']['triples']['format']= ['N-Triples','Notation 3','RDFXML','Turtle'];
$config['risearch']['aliases']['dc']="http://purl.org/dc/elements/1.1/";
$config['risearch']['aliases']['fedora-rels-ext']="info:fedora/fedora-system:def/relations-external#";
$config['risearch']['aliases']['mulgara']="http://mulgara.org/mulgara#";
$config['risearch']['aliases']['fedora-view']="info:fedora/fedora-system:def/view#";
$config['risearch']['aliases']['fedora-mod']="info:fedora/fedora-system:def/model#";
$config['risearch']['aliases']['xml-schema']="http://www.w3.org/2001/XMLSchema#";
$config['risearch']['aliases']['fedora-model']="info:fedora/fedora-system:def/model#";
$config['risearch']['aliases']['rdf']="http://www.w3.org/1999/02/22-rdf-syntax-ns#";
$config['risearch']['aliases']['fedora']="info:fedora/";
$config['risearch']['aliases']['jaf']="info:jaffedora/";
$config['risearch']['preds']['jaf']= ['isGroup','isCollection','isCollectionOf','hasCollectionLevel','isSubcollection','isItem','isItemOf','isTest'];
$config['risearch']['preds']['dc']= ['contributor'=>'Contributor','coverage'=>'Coverage','creator'=>'Creator','date'=>'Date','description'=>'Description','format'=>'Format','identifier'=>'Identifier','language'=>'Language','publisher'=>'Publisher','relation'=>'Relation','rights'=>'Rights','source'=>'Source','type'=>'Type','subject'=>'Subject','title'=>'Title'];
$config['risearch']['preds']['rels']= ['isPartOf','hasPart','isConstituentOf','hasConstituent','isMemberOf','hasMember','isSubsetOf','hasSubset','isMemberOfCollection','hasCollectionMember','isDerivationOf','hasDerivation','isDependentOf','hasDependent','isDescriptionOf','hasDescription','isMetadataFor','hasMetadata','isAnnotationOf','hasAnnotation','hasEquivalent'];
$config['risearch']['preds']['view']= ['mimeType','isVolatile','disseminates','disseminationType','lastModifiedDate'];
$config['risearch']['preds']['model']= ['hasModel','state','hasService','isDeploymentOf','isContractorOf','ownerId','label','createdDate'];
// GSearch
$config['gsearch']['fields']['dc']=array_keys($config['risearch']['preds']['dc']);
// Imagemanip
$config['imanip']['ops']= ['size','zoom','lite','mark','gray','crop','conv'];
$config['imanip']['formats']= ['gif','jpg','tiff','png','bmp'];
// KML
$config['kml']['empty']="<kml xmlns='https://www.opengis.net/kml/2.2'>
<Document><name>GPS-Photo Link</name><open>1</open><Style id='Photo'>
<IconStyle><scale>1.0</scale><Icon><href>http://maps.google.com/mapfiles/kml/pal4/icon46.png</href></Icon></IconStyle>
<LabelStyle><color>ffffffff</color><colorMode>normal</colorMode><scale>1.0</scale></LabelStyle></Style>
<Folder><name></name><open>1</open>**</Folder></Document></kml>";
// Saxon
$config['xmlfilepath']='files'.DS.'xml'.DS.'other';
$config['xsltfilepath']='files'.DS.'xsl';
$config['jsonfilepath']='files'.DS.'json';
