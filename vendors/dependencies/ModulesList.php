<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'DependenciesTask.php';
/**
 * Description of ModulesList
 *
 * @author leo
 */
class ModulesList {    
    private static $modules = array();
    
    public static function &GetModule($name) {
        if (!self::isModuleExist($name)) {
           self::$modules[$name] = new Module($name);
        }

        return self::$modules[$name];
    }
    
    public static function &GetModulesList() {
        return self::$modules;
    }
    
    public static function isModuleExist($name) {
        if (empty(self::$modules[$name])) {
            return false;
        } else {
            return true;
        }
    }
    
    
}
