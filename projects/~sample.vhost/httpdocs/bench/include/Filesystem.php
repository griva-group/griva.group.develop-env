<?php
class Filesystem
{
    private $bench;
    private $tmpDirPath;
    private $filenamePattern = "filesistemTest_";
    private $stepsNumber = 200;
    private $summaryTime = 0;
    private $content;
    public function __construct()
    {
        $this->bench = Main::getInstance();
        $this->content = str_repeat("x", 2048);
        if ($this->tmpDirPath = $this->createTmpDir()) {
            $this->correctionCalc();
            $this->testCreateFiles();
            $this->testReadFiles();
            $this->testDeleteFiles();
            $this->printSummaryInfo();
            $this->deleteTmpDir();
        }
    }

    /**
     * Пытаемся создать временную папку для тестирования файловых операций
     * @return bool|string
     */
    private function createTmpDir()
    {
        $tmpDirPath = __DIR__.DIRECTORY_SEPARATOR."filesystemTestDir";
        if (mkdir($tmpDirPath)) {
            return $tmpDirPath;
        } else {
            $this->bench->printString("ERROR: Temp dir is not created!");
            return false;
        }
    }

    /**
     * Подчищаем за скриптом тестирования
     */
    private function deleteTmpDir()
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->tmpDirPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        rmdir($this->tmpDirPath);
    }

    /**
     * Создает тестовый набор файлов
     */
    private function testCreateFiles()
    {
        $timeID = $this->bench->setExecutionTime();
        for ($i=0; $i < $this->stepsNumber; $i++) {
            $filePath = $this->tmpDirPath.DIRECTORY_SEPARATOR.$this->filenamePattern.$i;
            $fh = fopen($filePath, "wb");
            fwrite($fh, $this->content);
            fclose($fh);
        }
        $this->summaryTime += $time = $this->bench->getExecutionTime($timeID);
        $this->bench->printTimeResult("Create file (".$this->stepsNumber.")", $time);
    }

    /**
     * Считывает информацию из тестового набора файлов
     */
    private function testReadFiles()
    {
        $timeID = $this->bench->setExecutionTime();
        for ($i=0; $i < $this->stepsNumber; $i++) {
            $filePath = $this->tmpDirPath.DIRECTORY_SEPARATOR.$this->filenamePattern.$i;
            file_get_contents($filePath);
        }
        $this->summaryTime += $time = $this->bench->getExecutionTime($timeID);
        $this->bench->printTimeResult("Read file (".$this->stepsNumber.")", $time);
    }

    /**
     * Удаляет созданные файлы
     */
    private function testDeleteFiles()
    {
        $timeID = $this->bench->setExecutionTime();
        for ($i=0; $i < $this->stepsNumber; $i++) {
            $filePath = $this->tmpDirPath.DIRECTORY_SEPARATOR.$this->filenamePattern.$i;
            unlink($filePath);
        }
        $this->summaryTime += $time = $this->bench->getExecutionTime($timeID);
        $this->bench->printTimeResult("Delete file (".$this->stepsNumber.")", $time);
    }

    /**
     * Вычисляет временную поправку на преобразования переменных
     */
    private function correctionCalc()
    {
        $timeID = $this->bench->setExecutionTime();
        for($i = 0; $i < $this->stepsNumber; ++$i) {
            $filePath = $this->tmpDirPath.DIRECTORY_SEPARATOR.$this->filenamePattern.$i;
        }
        $this->summaryTime -= $time = $this->bench->getExecutionTime($timeID);
        $this->bench->printTimeResult("Correction time (".$this->stepsNumber.")", $time);
    }

    /**
     * Выводит сводную информацию о тесте
     */
    private function printSummaryInfo()
    {
        $this->bench->printString("");
        $timePerOperation = $this->summaryTime / ($this->stepsNumber * 3);
        $this->bench->printTimeResult("Total FS test time", $this->summaryTime);
        $this->bench->printTimeResult("Time per operation", $timePerOperation);

        $title = str_pad("Operations per second", ($this->bench->lineLength - 15)) . " : ";
        $result = str_pad(round(1 / $timePerOperation), 7, " ", STR_PAD_LEFT );
        $this->bench->printString($title.$result);
    }

    function testIfElse() {
        $timeID = $this->bench->setExecutionTime();
        $stepsNumber = $this->bench->getTestSteps() * 10000;
        for ($i=0; $i < $stepsNumber; $i++) {
            if ($i == -1) {
            } elseif ($i == -2) {
            } else if ($i == -3) {
            }
        }
        $time = $this->bench->getExecutionTime($timeID);
        $this->bench->printTimeResult("Create file", $time);
    }
}
