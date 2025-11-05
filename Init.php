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
    if (str_contains($path, '..')) {
        exit;
    }
    if (str_contains($path, '?')) {
        $path = substr($path, 0, strpos($path, '?'));
    }
    if(str_ends_with($path, '.css')) {
        header('content-type:text/css');
    }
    if(str_ends_with($path, '.js')) {
        header('content-type:application/javascript');
    }
    $fullPath = __DIR__.'/../BuildResults/Dist/'.$path;
    if (php_sapi_name() === 'cli-server') {
        echo file_get_contents($fullPath);
    } else {
        copy($fullPath, 'php://stdout');
    }
} else {
    include_once __DIR__.'/autoloader.php';
    include_once __DIR__.'/loadDotEnv.php';
    include_once __DIR__.'/globalFunctions.php';
    \Core\Database\DB::init();
    \Core\Routing\Router::routeHttp($_SERVER['REQUEST_URI']);

}
