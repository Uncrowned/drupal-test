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
    
    private $isLoaded = false;
    
    public function getName() {
        return $this->name;
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

    public function setRequiredByFromFunc($requiredByFromFunc) {
        $this->requiredByFromFunc = $requiredByFromFunc;
    }
    
    public function __construct($name = "") {
        $this->setName($name);
    }
    
    public function load($data) {
        $this->core = $data["core"];
        $this->date = $data["date"];
        $this->description = $data["description"];
        
        $this->extension = $data["extension"];
        $this->package = $data["package"];
        $this->path = $data["path"];
        
        $this->permissions = $data["permissions"];
        $this->phpVersion = $data["php"];
        $this->project = $data["project"];
        
        $this->requiredByFromInfo = $data["requiredBy"];
        $this->requiresFromInfo = $data["requires"];
        $this->schemaVersion = $data["schemaVersion"];
        
        $this->status = $data["status"];
        $this->type = $data["type"];
        $this->title = $data["title"];
        
        $this->version = $data["version"];
        
        $this->isLoaded = true;
    }
    
    public function isLoaded() {
        return $this->isLoaded;
    }
}
