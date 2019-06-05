<?php
define("BM_ROOT_DIR", __DIR__);
include "include/Main.php";
include "include/Functions.php";
include "include/Filesystem.php";

// Если запуск происходит в браузере - добавляем тег
if (php_sapi_name() !== "cli") {
    print "<pre>";
}
$bench = Main::getInstance();
$bench->setTestSteps(5000);
$bench->printHeader();

$bench->printHeadline("Functions");
new Functions();
$bench->printLine();

$bench->printHeadline("Filesystem");
new Filesystem();
$bench->printLine();

$bench->printTimeResult("Total time script", $bench->getExecutionTime('general'));

// Если запуск происходит в браузере - добавляем тег
if (php_sapi_name() !== "cli") {
    print "</pre>";
}
