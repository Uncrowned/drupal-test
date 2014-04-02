<?php
/*
 * Пара вопросов. 1 - регулярка, 2 - список объявленных функций мы составляем только из кастомных модулей или нет?
 * 3 - поиск вызывающих функций проводить только по кастомным модулям или нет?
 * 4 - регулярка на поиск объявленных функций
 */
require_once "/usr/share/pear/phing/Task.php";
require_once "GraphBuilder.php";

/**
 * Description of dependenceTag
 *
 * @author leo
 */
class DependenceTask extends Task {
    //mb here should be some refinements about regular expression
    //const REG_FUNC_DECLARE = "~[^/\"'][ ]*function[ ]+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*?\(~";
    const REG_FUNC_DECLARE = "~[^((\" *?)|(\/\/ *?))|(\/\* *?)|(\' *?))]function\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*?\s*?\(~";
    const REG_FUNC_KEY_WORD = "~function[ ]+?~";
    const REG_FUNC_CALL = "~[^((\" *?)|(\/\/ *?))|(\/\* *?)|(\' *?))][^(functions)]\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*?\s*?\(~";
    
    /**
     * Attribute for module paths list.
     */
    private $pathsList = null;
    
    /**
     * Attribute for declared functions.
     */
    private $declaredFuncs = null;
    
    /**
     * Attribute for called functions.
     */
    private $calledFuncs = null;
    
    /*
    * Attribute for base path.
     */
    private $basePath = null;
    
    /**
     * Attribute for array types of files, which will be scaned.
     */
    private $filesType = array();
    
    /**
     * Attribute for names of modules.
     */
    private $namesList = null;
    
    /**
     * Attribute for delimiter.
     */
    private $delimiter = null;
    
    /**
     * The setter for the attribute "pathsList"
     */
    public function setPathsList($list) {
        $this->pathsList = explode($this->delimiter, $list);
    }
    
    /**
     * The setter for the attribute "basePath"
     */
    public function setBasePath($path) {
        $this->basePath = $path;
    }
    
    /**
     * The setter for the attribute "namesList"
     */
    public function setNamesList($list) {
        $this->namesList = explode($this->delimiter, $list);
    }
    
    /**
     * The setter for the attribute "filesType"
     */
    public function setFilesType($filesType) {
        $this->filesType = $filesType;
    }
    
    /**
     * The setter for the attribute "delimiter"
     */
    public function setDelimiter($delimiter) {
        $this->delimiter = $delimiter;
    }
    
    /**
     * The init method: Do init steps.
     */
    public function init() {
        $this->setDelimiter(",");
        
        $filesType = array (
            0 => "*.module",
            1 => "*.inc",
            2 => "*.install"
        );
        $this->setFilesType($filesType);
    }
    
    /**
     * This method build table of declared functions
     */
    private function buildTableOfDeclare() {
        $i = 0;
        foreach ($this->pathsList as $path) {
            $this->scanFilesForDeclare($path, $this->namesList[$i]);
            $i++;
        }
        
        return $result;
    }
    
    /**
     * This method scan files on contain declare of functions 
     */
    private function scanFilesForDeclare($path, $moduleName) {
        $filesList = $this->getFilesList($path);

        foreach ($filesList as $file) {
            $counter = count($file);
            //as $fileList is array of arrays, we need to double cycle
            if ($counter > 1) {
                for ($i = 0; $i < $counter; $i++) {
                    $this->findFunctionsDeclare($file, $moduleName);
                }
            } else {
                $this->findFunctionsDeclare($file, $moduleName);
            }
        }
    }
    
    /**
     * This method 
 -   */
    private function findFunctionsDeclare($file, $moduleName) {
        //its need cuz argument file is array
        $file = implode("", $file);
        //temp solution, with function file_get_contents
        $content = file_get_contents($file);
        
        preg_match_all(self::REG_FUNC_DECLARE, $content, $matches, PREG_PATTERN_ORDER);
        
        foreach ($matches[0] as $match) {
            $functionName = preg_replace(self::REG_FUNC_KEY_WORD, "", $match);
            $functionName = str_replace("(", "", $functionName);
            $functionName = trim($functionName);
            
            //echo $functionName."=>".$moduleName."\n";
            $this->declaredFuncs[$functionName] = $moduleName;
        }
    }
    
    /**
     * This method 
 -   */
    private function getFilesList($path) {
        $typeCounter = count($this->filesType);
        
        for ($i = 0; $i < $typeCounter; $i++) {
            //glob function return array, even if directory will have only one such file
            $findedFiles = glob($this->basePath."/".$path."/".$this->filesType[$i]);
            
            if (count($findedFiles) != 0) {
                $result[] = $findedFiles;
            }
        }
        
        return $result;
    }
    
    /**
     * This method 
     */
    private function findFunctionsCall($file, $moduleName) {
        //its need cuz argument file is array
        $file = implode("", $file);
        //temp solution, with function file_get_contents
        $content = file_get_contents($file);
        //Надо разобраться с регуляркой на поиск вызовов функций
        preg_match_all(self::REG_FUNC_CALL, $content, $matches, PREG_PATTERN_ORDER);
        
        foreach ($matches[0] as $match) {
            //$functionName = preg_replace(self::REG_FUNC_KEY_WORD, "", $match);
            //$functionName = str_replace("(", "", $functionName);
            $functionName = str_replace("(", "", $match);
            
            $this->calledFuncs[$functionName][] = $moduleName;
        }
    }
    
    /**
     * This method 
     */
    private function scanFilesForCall($path, $moduleName) {
        $filesList = $this->getFilesList($path);
        
        foreach ($filesList as $file) {
            $counter = count($file);
            //as $fileList is array of arrays, we need to double cycle
            if ($counter > 1) {
                for ($i = 0; $i < $counter; $i++) {
                    $this->findFunctionsCall($file, $moduleName);
                }
            } else {
                $this->findFunctionsCall($file, $moduleName);
            }
        }
    }
    
    /**
     * This method build table of called functions
     */
    private function buildTableOfCall() {
        $i = 0;
        foreach ($this->pathsList as $path) {
            $this->scanFilesForCall($path, $this->namesList[$i]);
            $i++;
        }
    }

    /**
     * The main entry point method.
     */
    public function main() {
        //$this->buildTableOfDeclare();
        $this->buildTableOfCall();
        
        $graphBuilder = new GraphBuilder($this->declaredFuncs, $this->calledFuncs);
    }
}
