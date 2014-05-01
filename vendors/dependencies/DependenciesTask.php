<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "/usr/share/pear/phing/Task.php";

require_once 'XmlParser.php';
require_once 'Module.php';
require_once 'Function.php';

/**
 * Description of DependenciesTask
 *
 * @author leo
 */
class DependenciesTask extends Task{
    //const REG_FUNC_DECLARE = "[^((\" *?)|(\/\/ *?)|(\/\* *?)|(\' *?))]function[:space:]+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*?";
    //const REG_FUNC_DECLARE = "[^\"(\/\/)(\/\*)\'][:space:]*?function[:space:]+[a-zA-Z_][a-zA-Z0-9_]*?";
    //const REG_FUNC_DECLARE = "function[:space:]+?[a-zA-Z_][a-zA-Z0-9_]*?";
    const REG_FUNC_DECLARE = "function[:space:]+?";
    /**
     * Attribute for contain objects of modules
     */
    private $modules = null;
    
    private $basePath = null;
    
    private $filesType = null;
    
    /**
     * Attribute for contain object which can read xml file by given path
     */
    private $xml = null;
    
    public function setXml($xmlFilePath) {
        $this->xml = new XmlParser($xmlFilePath);
    }
    
    public function setBasePath($path) {
        $this->basePath = $path;
    }
    
    public function setFilesType($filesType) {
        $this->filesType = $filesType;
    }

     /**
     * The init method: Do init steps.
     */
    public function init() {
        $filesType = array (
            0 => "*.module",
            1 => "*.inc",
            2 => "*.install"
        );
        $this->setFilesType($filesType);
    }
    
    private function createModulesList() {
        $count = $this->xml->countModules();
        
        for ($i = 0; $i < $count; $i++) {
            $data = $this->getDataForModule($this->xml->getDataByIndex($i));
            
            if (!$this->isModuleExist($data["name"])) {
                $this->modules[$data["name"]] = new Module($data["name"]);
            }
            
            if (!$this->modules[$data["name"]]->isLoaded()) {
                $this->modules[$data["name"]]->load($data);
                $this->setDeclaredFunctions($data["name"]);
            }
            
            break;
        }
    }
    
    private function setDeclaredFunctions($name) {
        $path = $this->modules[$name]->getPath();
        $command = "egrep -h ".self::REG_FUNC_DECLARE." ";
        
        foreach ($this->filesType as $type) {
            $command .= $path."/".$type." ";
        }
        
        exec($command, $output);
        
        var_dump($output);
        //echo $command."\n";
    }
    
    private function isModuleExist($name) {
        if (empty($this->modules[$name])) {
            return false;
        } else {
            return true;
        }
    }
        
    private function checkRequiredModules($modules) {
        if (!empty($modules)) {
            $modules = explode(" ", $modules);
            
            foreach ($modules as $moduleName) {
                if (!empty($moduleName)) {
                    if (!$this->isModuleExist($moduleName)) {
                        $this->modules[$moduleName] = new Module($moduleName);
                    }
                    
                    $required[] = &$this->modules[$moduleName];
                    //$required[] = $moduleName;
                }
            }
        }
        
        return $required;
    }
    
    private function getDataForModule($data) {
        $newData["name"] = strip_tags($data->name->asXml());
        $newData["extension"] = strip_tags($data->extension->asXml());
        $newData["project"] = strip_tags($data->project->asXml());
        
        $newData["type"] = strip_tags($data->type->asXml());
        $newData["title"] = strip_tags($data->title->asXml());
        $newData["description"] = strip_tags($data->description->asXml());
        
        $newData["date"] = strip_tags($data->date->asXml());
        $newData["package"] = strip_tags($data->package->asXml());
        $newData["core"] = strip_tags($data->core->asXml());
        
        $newData["phpVersion"] = strip_tags($data->php->asXml());
        $newData["status"] = strip_tags($data->status->asXml());
        $newData["path"] = $this->basePath."/".strip_tags($data->path->asXml());
        
        $newData["schemaVersion"] = strip_tags($data->schema_version->asXml());
        $newData["permissions"] = strip_tags($data->permissions->asXml());
        $newData["version"] = strip_tags($data->version->asXml());
        
        $newData["requiredBy"] = $this->checkRequiredModules(strip_tags($data->required_by->asXml()));
        $newData["requires"] = $this->checkRequiredModules(strip_tags($data->requires->asXml()));
        
        return $newData;
    }
    
    /*public static function getModuleByName($name) {
        
    }
    
    public static function getModuleByIndex($index) {
        $module = &$this->modules[$index];
        return $module;
    }*/
    
    /**
     * The main entry point method.
     */
    public function main() {
        $this->createModulesList();
    }
}
