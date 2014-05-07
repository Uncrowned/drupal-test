<?php

require_once "/usr/share/pear/phing/Task.php";

class XmlParserTask extends Task {
    const CONDITION_DELIMITER = "=";
    /**
     * Attribute for module path.
     */
    private $file = null;
    
    /**
     * Attribute for condition.
     */
    private $condition = null;
    
    /**
     * Attribute for dom document.
     */
    private $xmlDomDoc = null;
    
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
     * The setter for the attribute "condition"
     */
    public function setCondition($condition) {
        $this->condition = $condition;
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
     * Method which return value in "tag", considering "condition"
     */
    public function getTagValue() {
        $this->xmlDomDoc = new DOMDocument();
        $this->xmlDomDoc->load($this->file);
        
        if (empty($this->condition)) {
            return $this->getTagWithoutCondition();
        } else {
            return $this->getTagWithCondition();
        }
    }
    
    /**
     * Method which return $this->tag value directly
     */
    public function getTagWithoutCondition() {
        $data = $this->xmlDomDoc->getElementsByTagName($this->tag);
        $return = array();

        foreach ($data as $row) {
            $return[] = $row->nodeValue;
        }

        $return = implode(",", $return);
        
        return $return;
    }
    
    /**
     * Method which return $this->tag value with condition $this->condition
     */
    public function getTagWithCondition() {
        // Короче надо подумать, как сделать этот метод получше, и нужен ли он вообще
        // Пусть пока будет так, но мне не очень нравится как это работает
        // Здесь мы выбираем нужный тег, из модуля, который удовлетворяет $this->condition
        $condition = explode(self::CONDITION_DELIMITER, $this->condition);
            
        $modules = $this->xmlDomDoc->firstChild->childNodes;
        //$modules = $this->xmlDomDoc->getElementsByTagName(XmlWriterTask::MODULE_TAG);
        $finded = false;
            
        foreach ($modules as $oneModule) {
            $fields = $oneModule->childNodes;
            for ($i = 0; $i < $fields->length; $i++) {
                if ($fields->item($i)->nodeName === $condition[0]) {
                    if ($fields->item($i)->nodeValue === $condition[1]) {
                        $finded = true;
                        break;
                    }
                }
            }
            if ($finded) {
                $resultFields = $oneModule->childNodes;
                break;
            }
        }

        if (!$finded) {
            throw new Exception("Can not find module with condition ".$this->condition);
        } else {
            foreach ($resultFields as $oneField) {
                if ($oneField->nodeName === $this->tag) {
                    return $oneField->nodeValue;
                }
            }
        }
        throw new Exception("Can not find tag <".$this->tag.">");
    }

    /**
     * The main entry point method.
     */
    public function main() {
        $this->project->setProperty($this->outputProperty, $this->getTagValue());
    }
}

?>