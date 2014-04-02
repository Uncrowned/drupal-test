<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GraphBuilder
 *
 * @author leo
 */
class GraphBuilder {
    
    /*
     *
     */
    private $declare;
    
    /*
     *
     */
    private $calls;
    
    /*
     *
     */
    private $graphOfModules;
    
    /*
     * 
     */
    public function __construct($declare, $calls) {
        $this->declare = $declare;
        $this->calls = $calls;
    }
    
    /*
     * 
     */
    public function setDeclaredFunctions($declare) {
        $this->declare = $declare;
    }
    
    /*
     * 
     */
    public function setCallsFunction($calls) {
        $this->calls = $calls;
    }
    
    /*
     * 
     */
    public function getGraph() {
        $this->buildGraph();
        
        return $this->graphOfModules;
    }
    
    /*
     * 
     */
    private function buildGraph() {
        foreach ($this->declare as $function => $module) {
            $this->findCompliance($function, $module);
        }
    }
    
    /*
     * 
     */
    private function findCompliance($declareFunc, $declareModule) {
        foreach ($this->calls as $callFunction => $modules) {
            if ($declareFunc == $callFunction) {
                $this->checkModules($callFunction, $declareModule);
            }
        }
    }
    
    /*
     * 
     */
    private function checkModules($callFunction, $declareModule) {
        $i = 0;
        
        foreach ($this->calls[$callFunction] as $module) {
            if ($module == $declareModule) {
                unset($this->calls[$callFunction][$i]);
                $this->calls[$callFunction] = array_values($this->calls[$callFunction]);
                
                if (!empty($this->calls[$callFunction])) {
                    $this->graphOfModules[$declareModule] = $this->calls[$callFunction];
                }
            }
            
            $i++;
        }
    }
}
