<?php

require_once "/usr/share/pear/phing/Task.php";

class XmlParserTask extends Task {

    /**
     * Attribute for module name.
     */
    private $file = null;
    
    /**
     * Attribute for tag, which will be obtain
     */
    private $tag = null;
    
    /**
     * Attribute for property, in which one will be set a list of values
     */
    private $outputProperty = null;
    /**
     * The setter for the attribute "file"
     */
    public function setFile($path) {
        $this->file = $path;
    }
    
    /**
     * The setter for the attribute "tag"
     */
    public function setTag($name) {
        $this->tag = $name;
    }
    
    /**
     * The setter for the attribute "outputProperty"
     */
    public function setOutputProperty($name) {
        $this->outputProperty = $name;
    }
    
    /**
     * The init method: Do init steps.
     */
    public function init() {
        // nothing to do here
    }
    
    /**
     * Method for parsing xml file
     */
    public function parse() {
        $xml = new DOMDocument();
        $xml->load($this->file);
        
        $data = $xml->getElementsByTagName($this->tag);
        $return = array();
        
        foreach ($data as $row) {
            $return[] = $row->nodeValue;
        }
        
        $return = implode(",", $return);
        $this->project->setProperty($this->outputProperty, $return);
    }

    /**
     * The main entry point method.
     */
    public function main() {
        //print($this->message);
        $this->parse();
    }
}

?>