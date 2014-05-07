<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Module
 *
 * @author leo
 */
class Module {
    
    private $name = null;
    
    private $extension = null;
    
    private $project = null;
    
    private $type = null;
    
    private $title = null;
    
    private $description = null;
    
    private $version = null;
    
    private $date = null;
    
    private $package = null;
    
    private $core = null;
    
    private $phpVersion = null;

    private $status = null;
    
    private $path = null;
    
    private $schemaVersion = null;
    
    private $permissions = null;
    
    private $requiresFromInfo = null;
    
    private $requiredByFromInfo = null;
    
    private $declaredFunctions = null;
    
    private $requiredByFromFunc = null;
    
    private $regExp = null;
    
    private $isLoaded = false;
    
    public function getName() {
        return $this->name;
    }
    
    public function getRegExp() {
        return $this->regExp;
    }
    
    public function getRequiresFromInfo() {
        return $this->requiresFromInfo;
    }
    
    public function getExtension() {
        return $this->extension;
    }

    public function getProject() {
        return $this->project;
    }

    public function getType() {
        return $this->type;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getDate() {
        return $this->date;
    }

    public function getPackage() {
        return $this->package;
    }

    public function getCore() {
        return $this->core;
    }

    public function getPhpVersion() {
        return $this->phpVersion;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getPath() {
        return $this->path;
    }

    public function getSchemaVersion() {
        return $this->schemaVersion;
    }

    public function getPermissions() {
        return $this->permissions;
    }

    public function getRequiredByFromInfo() {
        return $this->requiredByFromInfo;
    }

    public function getDeclaredFunctions() {
        return $this->declaredFunctions;
    }

    public function getRequiredByFromFunc() {
        return $this->requiredByFromFunc;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDeclaredFunctions($declaredFunctions) {
        $this->declaredFunctions = $declaredFunctions;
    }
    
    public function appendDeclaredFunction($function) {
        $this->declaredFunctions[] = $function;
    }
    
    public function insertDeclaredFunction($index, $function) {
        $this->declaredFunctions[$index] = $function;
    }
    
    public function removeDeclaredFunction($index) {
        unset($this->declaredFunctions[$index]);
        $this->declaredFunctions = array_values($this->declaredFunctions);
    }
    
    public function clearDeclaredFunctions() {
        array_splice($this->declaredFunctions, 0);
    }
    
    public function setRegExp($regExp) {
        $this->regExp = $regExp;
    }
    
    public function setRequiresFromInfo($requiresFromInfo) {
        $this->requiresFromInfo = $requiresFromInfo;
    }
    
    public function setRequiredByFromInfo($requiredByFromInfo) {
        $this->requiredByFromInfo = $requiredByFromInfo;
    }
    
    public function setRequiredByFromFunc($requiredByFromFunc) {
        $this->requiredByFromFunc = $requiredByFromFunc;
    }
    
    public function __construct($name = "") {
        $this->setName($name);
    }
    
    public function appendRequiredByFunction(&$module) {
        $this->requiredByFromFunc[] = $module;
    }
    
    public function removeRequiredByFunction($index) {
        unset($this->requiredByFromFunc[$index]);
        $this->requiredByFromFunc = array_values($this->requiredByFromFunc);
    }
    
    public function insertRequiredByFunction($index, &$module) {
        $this->requiredByFromFunc[$index] = $module;
    }
    
    public function clearRequiredByFunction() {
        array_splice($this->requiredByFromFunc, 0);
    }
    
    public function load($data) {
        $this->core = (string)$data->core;
        $this->date = (string)$data->date;
        $this->description = (string)$data->description;
        
        $this->extension = (string)$data->extension;
        $this->package = (string)$data->package;
        $this->path = (string)$data->path;
        
        $this->permissions = (string)$data->permissions;
        $this->phpVersion = (string)$data->php;
        $this->project = (string)$data->project;
        
        $this->status = (string)$data->status;
        $this->type = (string)$data->type;
        $this->title = (string)$data->title;
        
        $this->schemaVersion = (string)$data->schema_version;
        $this->version = (string)$data->version;
        
        $this->isLoaded = true;
    }
    
    public function isLoaded() {
        return $this->isLoaded;
    }
}
