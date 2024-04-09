<?php

use Core\Routing\ComponentRouter;

error_reporting(E_ALL);
ini_set("log_errors", 1);
if (!is_dir(__DIR__."/../Tmp")) mkdir(__DIR__."/../Tmp");
ini_set("error_log", __DIR__."/../Tmp/php-error.log");
function t($q)
{
    try {
        return \Core\Internationalization\Translator::$default->translate($q)->__toString();
    } catch (\Throwable $ex) {
        return '[['.$q.']]';
    }
}

include_once __DIR__.'/Debug.php';
if (strpos($_SERVER['REQUEST_URI'], '/Dist/') === 0) {
    $path = substr($_SERVER['REQUEST_URI'], 6);
    copy(__DIR__.'/../BuildResults/Dist/'.$path, 'php://stdout');
} else {
    include_once __DIR__.'/autoloader.php';
    include_once __DIR__.'/loadDotEnv.php';
    include_once __DIR__.'/globalFunctions.php';
    \Core\Database\DB::init();
    \Core\Routing\Router::routeHttp($_SERVER['REQUEST_URI']);
}
