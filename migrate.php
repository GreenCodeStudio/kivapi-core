<?php

error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", __dir__."/Tmp/php-error.log");

include_once __DIR__.'/autoloader.php';
include_once __DIR__.'/loadDotEnv.php';
\Core\Database\DB::init();
$migration = new \Core\Database\Migration();
$migration->upgrade();
$migration->execute();
