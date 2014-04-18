<?php
require_once "/usr/share/pear/phing/Task.php";
/**
 * Description of dependenceTag
 *
 * @author leo
 */
class DependenceTask extends Task {
    const REG_FUNC_DECLARE = "~[^((\" *?)|(\/\/ *?)|(\/\* *?)|(\' *?))]function\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*?\s*?\(~";
    const REG_FUNC_KEY_WORD = "~function[ ]+?~";
    const REG_FUNC_CALL = "(?<!function)\s+?";
    
    /**
     * Attribute contain "module" => "function1|function2 and other".
     */
    private $requestsOnModule= null;
    
    /**
     * Attribute for module paths list.
     */
    private $pathsList = null;
    
    /**
     * Attribute for declared functions.
     */
    private $declaredFuncs = null;
    
    /**
     * Attribute which store graph dependencies of modules.
     */
    private $graphOfModules= null;
    
   /**
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
    }
    
    /**
     * This method scan files on contain declare of functions 
     */
    private function scanFilesForDeclare($path, $moduleName) {
        $filesList = $this->getFilesList($path);
        
        if (!self::isMassiveEmpty($filesList)) {
            foreach ($filesList as $file) {
                $counter = count($file);
                //as $fileList is array of arrays, we need to double cycle
                if ($counter > 1) {
                    for ($i = 0; $i < $counter; $i++) {
                        //echo $file[$i]."\n";
                        $this->findFunctionsDeclare($file[$i], $moduleName);
                    }
                } else {
                    $this->findFunctionsDeclare($file[0], $moduleName);
                }
            }
        }
    }
    
    /**
     * This method 
 -   */
    private function findFunctionsDeclare($file, $moduleName) {
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
    private function buildMassiveOfRequest() {
        $spaceRegEx = "\s*?\(|";
        
        $currentModule = current($this->declaredFuncs);
        $this->requestsOnModule[$currentModule] = "(".key($this->declaredFuncs).$spaceRegEx;
        next($this->declaredFuncs);
        
        while ($module = current($this->declaredFuncs)) {
            if ($module == $currentModule) {
                $this->requestsOnModule[$currentModule] .= key($this->declaredFuncs).$spaceRegEx;
            } else {
                $currentModule = $module;
                $this->requestsOnModule[$currentModule] = "(".key($this->declaredFuncs).$spaceRegEx;
            }
            next($this->declaredFuncs);
        }
        
        foreach ($this->requestsOnModule as $module => $functions) {
            $this->requestsOnModule[$module] = substr($functions, 0, -1).")";
            //echo $module." => ".$this->requestsOnModule[$module]."\n";
        }
    }
    
    /**
     * This method 
     */
    private function buildGraphByCalls($file, $verifiableModule) {
        $content = file_get_contents($file);
        
        foreach ($this->requestsOnModule as $module => $request) {
            if ($module != $verifiableModule) {
                $regexp = "~".self::REG_FUNC_CALL.$request."~";
                //echo $file."\n";
                //echo $regexp."\n\n";
                preg_match_all($regexp, $content, $matches, PREG_PATTERN_ORDER);
            }
            
            if (!self::isMassiveEmpty($matches)) {
                $this->graphOfModules[$verifiableModule] = $module;
            }
        }
    }
    
    public static function isMassiveEmpty($arr) {
        if (empty($arr)) 
            return true; // если уже пусто
        else {
          if (is_array($arr)) {
              foreach ($arr as $a) {
                  if (self::isMassiveEmpty($a)) 
                      return true; // рекурсивный вызов функцией самой себя, но на один уровень массива глубже
              }
              return false;
          }
          else // для простых переменных
            return empty( $arr ); 
        }
        return true;
    }
    
    private function scanFilesForCall($path, $moduleName) {
        $filesList = $this->getFilesList($path);
        
        if (!self::isMassiveEmpty($filesList)) {
            foreach ($filesList as $file) {
                $counter = count($file);
                //as $fileList is array of arrays, we need to double cycle
                if ($counter > 1) {
                    for ($i = 0; $i < $counter; $i++) {
                        $this->buildGraphByCalls($file[$i], $moduleName);
                    }
                } else {
                    $this->buildGraphByCalls($file[0], $moduleName);
                }
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
        $this->buildTableOfDeclare();
        $this->buildMassiveOfRequest();
        echo count($this->requestsOnModule)."\n";
        echo count($this->declaredFuncs);
        $this->buildTableOfCall();
        var_dump($this->graphOfModules);
    }
}
