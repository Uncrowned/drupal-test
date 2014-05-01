<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of Function class
 *
 * @author leo
 */
class FunctionObject {
    
    private $module = null;
    
    private $file = null;

    private $name = null;
    
    public function getModule() {
        return $this->module;
    }

    public function getFile() {
        return $this->file;
    }

    public function getName() {
        return $this->name;
    }

    public function setModule(&$module) {
        $this->module = $module;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    public function __construct($name, &$module) {
        $this->setName($name);
        $this->setModule($module);
    }
}
