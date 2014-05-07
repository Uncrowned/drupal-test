<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "/usr/share/pear/phing/Task.php";
require_once 'ModulesList.php';
require_once 'Module.php';
require_once 'Function.php';

/**
 * Description of DependenciesTask
 *
 * @author leo
 */
class DependenciesTask extends Task{
    //Надо дописать регулярку, пока она находит функции закомментированные, и те, что являются строкой, не знаю как это обойти
    const REG_FUNC_DECLARE = "function[[:space:]]\{1,\}[a-zA-Z_][a-zA-Z0-9_]\{0,\}";
    const EGREP = "egrep -h -s ";
    
    private $basePath = null;
    
    private $filesType = null;
    
    /**
     * Attribute for contain object which can read xml file by given path
     */
    private $xml = null;
    
    public function setXml($xmlFilePath) {
        //$this->xml = new XmlParser($xmlFilePath);
        $this->xml = simplexml_load_file($xmlFilePath);
        
        if (!$this->xml) {
            echo "Can't open file ".$this->file."\n";
            foreach(libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
        }
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
        $modulesXml = $this->xml->xpath('/modules/module');
        
        foreach ($modulesXml as $xmlObject) {
            $module = &ModulesList::GetModule((string)$xmlObject->name);

            if (!$module->isLoaded()) {
                $module->load($xmlObject);
                $module->setRequiredByFromInfo($this->checkRequiredModules((string)$xmlObject->required_by));
                $module->setRequiresFromInfo($this->checkRequiredModules((string)$xmlObject->requires));

                $this->setDeclaredFunctions($module->getName());
            }
        }
    }
    
    private function setDeclaredFunctions($name) {
        $module = &ModulesList::GetModule($name);
        $path = $this->basePath."/".$module->getPath();
        $command = DependenciesTask::EGREP.self::REG_FUNC_DECLARE." ";
        $regExp = ""; 
        
        foreach ($this->filesType as $type) {
            $command .= $path."/".$type." ";
        }
        
        exec($command, $output);
        
        foreach ($output as $string) {
            $string = substr($string, 0, strpos($string, "("));
            $string = str_replace(array("function ", " "), "", $string);
            
            if (!empty($string)) {
                $function = new FunctionObject($string, $module);
                $regExp .= $string."[[:space:]]\{0,\}[\(]\|"; 
                $module->appendDeclaredFunction($function);
            }
        }
        
        $regExp = substr_replace($regExp, "", -1, 2);
        $module->setRegExp($regExp);
    }
    
    private function execEgrep($path, $regExp) {
        $command = self::EGREP.$regExp." ";
                        
        foreach ($this->filesType as $type) {
            $command .= $path."/".$type." ";
        }
                        
        exec($command, $output);
        
        if (!empty($output)) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Temporary function, im not sure that is optimal
     */
    private function buildDependencies() {
        $modulesList = &ModulesList::GetModulesList();
        foreach ($modulesList as $module) {
            reset($modulesList);
            //echo "main ".$module->getName()."\n";
            
            while($current = current($modulesList)) {
                if ($module->getName() != $current->getName()) {
                    if ($current->isLoaded() && $module->isLoaded()) {
                        if ($this->execEgrep($this->basePath."/".$module->getPath(), $current->getRegExp())) {
                            $module->appendRequiredByFunction($current);
                        }
                    }
                }
                
                next($modulesList);
            }
        }
    }
        
    private function checkRequiredModules($modules) {
        if (!empty($modules)) {
            $modules = explode(" ", $modules);
            
            foreach ($modules as $moduleName) {
                if (!empty($moduleName)) {
                    $module = &ModulesList::GetModule($moduleName);
                    $required[] = $module;
                }
            }
        }
        
        return $required;
    }
    
    /**
     * The main entry point method.
     */
    public function main() {
        $this->createModulesList();
        $this->buildDependencies();
    }
}
