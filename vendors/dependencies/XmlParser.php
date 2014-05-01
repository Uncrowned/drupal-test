<?php

class XmlParser {

    /**
     * Attribute for module path.
     */
    private $file = null;
    
    /**
     * Attribute for dom document.
     */
    private $xmlDoc = null;
        
    /**
     * The setter for the attribute "file"
     */
    public function setFile($path) {
        $this->file = $path;
        $this->setXmlDoc();
    }
    
    /**
     * The setter for the attribute "xmlDoc"
     */
    private function setXmlDoc() {
        $this->xmlDoc = simplexml_load_file($this->file);
                
        if (!$this->xmlDoc) {
            echo "Can't open file ".$this->file."\n";
            foreach(libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
        }
    }
    
    /**
     * Method which return data about module with name = $name
     */
    public function getDataByName($name) {
        foreach ($this->xmlDoc as $module) {
            if ($module->name == $name) {
                return $module;
            }
        }
        
        return NULL;
    }
    
    /**
     * Method which return data about module with index = $index
     */
    public function getDataByIndex($index) {
        return $this->xmlDoc->module[$index];    
    }
    
    /**
     * The main entry point method.
     */
    public function __construct($file) {
        $this->setFile($file);
    }
    
    public function countModules() {
        return $this->xmlDoc->count();
    }
            
}

?>