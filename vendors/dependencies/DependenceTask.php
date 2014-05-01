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
    
    /*
     * 
     */
    private $modules = null;
    
    /**
     * Attribute for modules path list.
     */
    private $pathsList = null;
    
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
     * Attribute delimiter for explode pathsList and namesList
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
        foreach($this->modules as $name => $value) {
            $this->scanFilesForDeclare($this->modules[$name]["filesList"], $name);
        }
    }
    
    /**
     * This method scan files on contain declare of functions 
     */
    private function scanFilesForDeclare($filesList, $moduleName) {
        if (!self::isMassiveEmpty($filesList)) {
            //пробегаем по массиву filesList, а потом еще по каждому элементу этого массива
            foreach ($filesList as $files) {
                $this->findFunctionsDeclare($files, $moduleName);
            }
        }
    }
    
    /**
     * This method 
 -   */
    private function findFunctionsDeclare($files, $moduleName) {
        foreach ($files as $file) {
            $content = file_get_contents($file);
            preg_match_all(self::REG_FUNC_DECLARE, $content, $matches, PREG_PATTERN_ORDER);
        
            foreach ($matches[0] as $match) {
                $functionName = preg_replace(self::REG_FUNC_KEY_WORD, "", $match);
                $functionName = str_replace("(", "", $functionName);
                $functionName = trim($functionName);

                $this->modules[$moduleName]["declaredFunctions"][] = $functionName;
            }
        }
    }
    
    /**
     * Метод для получения списка файлов, которые будут исследованны.
 -   */
    private function getFilesList() {
        $typeCounter = count($this->filesType);
        $j = 0;
        
        foreach($this->pathsList as $path) {
            //для каждого пути находим файлы с нужным расширением
            for ($i = 0; $i < $typeCounter; $i++) {
                //glob function return array, even if directory will have only one such file
                $findedFiles = glob($this->basePath."/".$path."/".$this->filesType[$i]);
                /* и если что то нашли то добавляем в свойство, с ключем имени модуля.
                 * тут пропадают модули которе не содержат файлов с расширениями из массива filesType
                 */
                if (!self::isMassiveEmpty($findedFiles)) {
                    /* получается "filesList" это массив, который хранит массивы из путей до файлов,
                     * дело в том, что глоб возвращает массив даже если найдет только 1 фаил.
                     */
                    $this->modules[$this->namesList[$j]]["filesList"][] = $findedFiles;
                }
            }
            //счетчик j нужен для того, что бы выбирать соответствующие имена из массива имен (namesList)
            $j++;
        }
        //echo "paths list = ".count($this->pathsList)."\n";
        //echo "modules = ".count($this->modules)."\n"; 
    }
    
    /**
     * Метод строит массив регулярок, в каждой регулярке все найденные объявленные функции, 
     * для конкретного модуля
     */
    private function buildMassiveOfRequest() {
        $spaceRegEx = "\s*?\(";
        
        foreach($this->modules as $name => $value) {
            $declaredFunctions = $this->modules[$name]["declaredFunctions"];

            if (!empty($declaredFunctions)) {
                $this->modules[$name]["RegExpRequest"] = 
                        implode($spaceRegEx."|", $declaredFunctions);
                
                $this->modules[$name]["RegExpRequest"] = 
                        "(".$this->modules[$name]["RegExpRequest"].$spaceRegEx.")";
            }
        }
    }
    
    /**
     * This method 
     */
    private function appendInGraphByCalls($files, $verifiableModule) {
        foreach ($files as $file) {
            $content = file_get_contents($file);

            foreach ($this->modules as $name => $value) {
                if ($name != $verifiableModule) {
                    if (!empty($this->modules[$name]["RegExpRequest"])) {
                        $regexp = "~".self::REG_FUNC_CALL.$this->modules[$name]["RegExpRequest"]."~";
                        preg_match_all($regexp, $content, $matches, PREG_PATTERN_ORDER);

                        if (!self::isMassiveEmpty($matches)) {
                            $this->modules[$name]["dependencies"][] = &$this->modules[$verifiableModule];
                        }
                    }
                }
            }
        }
    }
    
    public static function isMassiveEmpty($arr) {
        if (empty($arr)) {
            return true; // если уже пусто
        }
        else {
          if (is_array($arr)) {
              foreach ($arr as $a) {
                  if (self::isMassiveEmpty($a)) 
                      return true; // рекурсивный вызов функцией самой себя, но на один уровень массива глубже
              }
              return false;
          }
          else {
              return empty($arr); // для простых переменных
          }
        }
        return true;
    }
    
    private function scanFilesForCall($filesList, $moduleName) {
        if (!self::isMassiveEmpty($filesList)) {
            foreach ($filesList as $files) {
                $this->appendInGraphByCalls($files, $moduleName);
            }
        }
    }
    
    /**
     * This method build table of called functions
     */
    private function buildDependencies() {
        foreach($this->modules as $name => $value) {
            $this->scanFilesForCall($this->modules[$name]["filesList"], $name);
        }
    }

    /**
     * The main entry point method.
     */
    public function main() {
        $this->getFilesList();
        $this->buildTableOfDeclare();
        $this->buildMassiveOfRequest();
        $this->buildDependencies();
    }
}
