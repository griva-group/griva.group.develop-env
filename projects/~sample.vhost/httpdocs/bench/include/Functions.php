<?php
class Functions
{
    private $bench;
    public function __construct()
    {
        $this->bench = Main::getInstance();
        $this->testMath();
        $this->testStringManipulation();
        $this->testLoops();
        $this->testIfElse();
    }

    /**
     * Тестирует математические функции
     */
    function testMath() {
        $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");

        foreach ($mathFunctions as $key => $function) {
            if (!function_exists($function)) unset($mathFunctions[$key]);
        }

        $stepsNumber = $this->bench->getTestSteps() * 100;
        $timeID = $this->bench->setExecutionTime();
        for ($i=0; $i < $stepsNumber; $i++) {
            foreach ($mathFunctions as $function) {
                $r = call_user_func_array($function, array($i));
            }
        }
        $time = $this->bench->getExecutionTime($timeID);
        $this->bench->printTimeResult("Math func (x100)", $time);
    }

    /**
     * Тестируем функции для манипуляции с текстом
     */
    function testStringManipulation() {
        $string = "the quick brown fox jumps over the lazy dog";
        $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");
        foreach ($stringFunctions as $key => $function) {
            if (!function_exists($function)) unset($stringFunctions[$key]);
        }

        $stepsNumber = $this->bench->getTestSteps() * 100;
        $timeID = $this->bench->setExecutionTime();
        for ($i=0; $i < $stepsNumber; $i++) {
            foreach ($stringFunctions as $function) {
                $r = call_user_func_array($function, array($string));
            }
        }
        $time = $this->bench->getExecutionTime($timeID);
        $this->bench->printTimeResult("String manipulations (x100)", $time);
    }

    /**
     * Прогоняет огромное кол-во раз циклы без вычислений
     */
    function testLoops() {
        $stepsNumber = $this->bench->getTestSteps() * 100000;
        $timeID = $this->bench->setExecutionTime();
        for($i = 0; $i < $stepsNumber; ++$i);
        $i = 0; while($i < $stepsNumber) ++$i;
        $time = $this->bench->getExecutionTime($timeID);
        $this->bench->printTimeResult("Loop (for, while) (x100000)", $time);
    }

    /**
     * Прогоняет огромное кол-во раз ложные условия всех конструкций
     */
    function testIfElse() {
        $stepsNumber = $this->bench->getTestSteps() * 10000;
        $timeID = $this->bench->setExecutionTime();
        for ($i=0; $i < $stepsNumber; $i++) {
            if ($i == -1) {
            } elseif ($i == -2) {
            } else if ($i == -3) {
            }
        }
        $time = $this->bench->getExecutionTime($timeID);
        $this->bench->printTimeResult("If,elseif,else (x10000)", $time);
    }
}
