<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the saxon service
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Saxon extends AppModel
{

    public $useTable=false;

    /**
     * Transform XML
     * @param String $xml
     * @param String $xsl
     * @param String $ext
     * @param String $pubid
     * @return boolean
     */
    public function transform($xml,$xsl,$ext)
    {
        // Assumes nothing about locations of files
        $spath="/opt/local/share/java/saxon9he.jar";
        $x=WWW_ROOT.Configure::read('xmlfilepath');
        $t=WWW_ROOT.Configure::read('xsltfilepath');
        $j=WWW_ROOT.Configure::read('jsonfilepath');
        $xpath=$x.DS.$xml;
        $tpath=$t.DS.$xsl;
        $jpath=$j.DS.str_replace("xml",$ext,$xml);
        if(file_exists($jpath)) {
            chmod($jpath, 0777);
        }
        $command="/usr/bin/java -jar ".$spath." -s:".$xpath." -xsl:".$tpath." -o:".$jpath;
        //echo $command;exit;
        exec($command);
        return file_exists($jpath);
    }
}