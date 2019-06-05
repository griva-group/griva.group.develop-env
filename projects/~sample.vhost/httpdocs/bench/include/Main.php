<?php
class Main
{
    private $config;
    private $steps = 10;
    private $timeQueue = [];

    public function __construct()
    {
        $this->config = include BM_ROOT_DIR.DIRECTORY_SEPARATOR."config.php";
    }

    /**
     * Задаем кол-во повторений тестов
     * @param $steps
     */
    public function setTestSteps($steps)
    {
        $this->steps = intval($steps);
    }

    /**
     * Возвращаем число повторений теста
     * @return int
     */
    public function getTestSteps()
    {
        return $this->steps;
    }

    /**
     * Регистрирует новую метку времени, вовзращая ид записи
     * @param string $index ИД записи
     * @return string ИД записи
     */
    public function setExecutionTime($index = null)
    {
        if (is_null($index)) $index = md5(microtime(true));
        if (!isset($this->timeQueue[$index])) {
            $this->timeQueue[$index] = microtime(true);
        }
        return $index;
    }

    /**
     * Возвращает ко-во секунд с дробной частью, прошедших с момента
     * регистрации метки времени
     * @param string $index ИД метки
     * @param bool $start Необходимо возвратить начальную метку времени
     * @return float
     */
    public function getExecutionTime($index, $start = false)
    {
        if ($start) return $this->timeQueue[$index];
        else return microtime(true) - $this->timeQueue[$index];
    }

    /**
     * @var int Стандартное число символов в строке
     */
    public $lineLength = 42;

    /**
     * Выводит небольшую шапку теста с кратким обзором входных данных
     */
    public function printHeader()
    {
        $this->printLine();
        $this->printHeadline("[GG] PHP LIGHT BENCHMARK", true);
        $this->printLine();
        $this->printSystemInfo();
        $this->printLine();
    }

    /**
     * Выводит горизонтальную линию
     */
    public function printLine()
    {
        print str_pad("-",$this->lineLength,"-")."\n";
    }

    /**
     * Выводит стандартизированный заголовок
     * @param string $text
     * @param bool $withBorder Необходимо ли выводить рамки
     */
    public function printHeadline($text, $withBorder = false)
    {
        if ($withBorder) {
            $text = "|".str_pad($text,($this->lineLength - 2)," ",STR_PAD_BOTH)."|";
        } else {
            $text = str_pad($text,$this->lineLength - 2," ",STR_PAD_BOTH);
        }
        print $text."\n";
    }

    /**
     * Выводит стандартизированную строку представления времени
     * @param string $title Текст строки-описания
     * @param float $time Метка времени
     */
    public function printTimeResult($title, $time)
    {
        $time = number_format($time, 3);
        print str_pad($title, ($this->lineLength - 15)) . " : ";
        print str_pad($time, 7, " ", STR_PAD_LEFT ) ." sec."."\n";
    }

    /**
     * Выводит переданный методу текст, добавляя символ перевода строки
     * @param string $text
     */
    public function printString($text)
    {
        print $text."\n";
    }

    /**
     * Выводит информацию о сервере
     */
    private function printSystemInfo()
    {
        $this->setExecutionTime('general');
        print "Start : ".date("Y-m-d H:i:s", $this->getExecutionTime('general', true))."\n";
        print "Server : {$_SERVER['SERVER_NAME']}@{$_SERVER['SERVER_ADDR']}\n";
        print "PHP version : ".PHP_VERSION."\n";
        print "Platform : ".PHP_OS. "\n";
        print "Steps number : ".$this->getTestSteps(). "\n";
    }

    /**
     * Реализация паттерна Singleton
     * @return Main
     */
    public static function getInstance()
    {
        static $instance;
        if (!$instance) $instance = new self();
        return $instance;
    }
}
