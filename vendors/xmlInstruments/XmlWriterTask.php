<?php

require_once "/usr/share/pear/phing/Task.php";

class XmlWriterTask extends Task {
    const UNKNOWN = "Unknown";
    const PROJECT_TAG = "project";
    const FIELD_NAME = "name";
    const MODULE_TAG = "module";
    /**
     * Attribute for module, which will be parse
     */
    private $module = null;
    
    /**
     * Attribute for module path.
     */
    private $contributeFile = null;
    
    /**
     * Attribute for module path.
     */
    private $customFile = null;
    
    /**
     * Attribute for token
     */
    private $token = null;
    
    /**
     * Attribute for delimiter
     */
    private $delimiter = null;
    
    /**
     * The setter for the attribute "Delimiter"
     */
    public function setDelimiter($delimiter) {
        $this->delimiter = $delimiter;
    }
    
    /**
     * The setter for the attribute "Contribute file"
     */
    public function setContributeFile($path) {
        $this->contributeFile = $path;
    }
    
    /**
     * The setter for the attribute "Custom file"
     */
    public function setCustomFile($path) {
        $this->customFile = $path;
    }
    
    /**
     * The setter for the attribute "token"
     */
    public function setToken($token) {
        $this->token = $token;
    }
    
    /**
     * The setter for the attribute "Module"
     */
    public function setModule($data) {
        $this->module = $data;
    }
    
    /**
     * The init method: Do init steps.
     */
    public function init() {
        $this->setDelimiter("\n");
        $this->setToken("=");
    }
    
    /**
     * Method for parsing input data
     */
    public function parse() {
        $tempModuleInfo = explode($this->delimiter, $this->module);
        $size = count($tempModuleInfo);
        
        $moduleInfo[self::FIELD_NAME] = trim($tempModuleInfo[0], "[]");
        
        for ($i = 1; $i < $size; $i++) {
            $field = strtok($tempModuleInfo[$i], $this->token);
            $value = strtok($this->token);
            
            $moduleInfo[$field] = $value;
        }
        
        return $moduleInfo;
    }
    
    /**
     * Method for write data into xml file.
     */
    public function writeIntoXml($moduleInfo, $file) {
        $xml = new DOMDocument();
        $xml->load($file);
        
        $modules = $xml->firstChild;
        $module = $xml->createElement(self::MODULE_TAG);

        foreach ($moduleInfo as $key => $value) {
            $infoTag = $xml->createElement($key);
            $infoTag->nodeValue = $value;
            
            $module->appendChild($infoTag);
        }
        
        $modules->appendChild($module);
        file_put_contents($file, $xml->saveXML());
    }
    
    /**
     * The main entry point method.
     */
    public function main() {
        $info = $this->parse();
        
        if ($info[self::PROJECT_TAG] === self::UNKNOWN) {
            $this->writeIntoXml($info, $this->customFile);
        } else {
            $this->writeIntoXml($info, $this->contributeFile);
        }
    }
}

?>