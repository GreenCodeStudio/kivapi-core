<?php
include_once __DIR__.'/../config.php';
spl_autoload_register(function ($class_name) {
    if (substr($class_name, 0, 5) == 'Core\\') {
        $path = __DIR__.'/'.str_replace("\\", "/", substr($class_name, 5)).'.php';
        if (file_exists($path))
            include_once $path;
    } else if (substr($class_name, 0, strlen(MAIN_NAMESPACE)) == MAIN_NAMESPACE) {
        $path = __DIR__.'/../'.str_replace("\\", "/", substr($class_name, strlen(MAIN_NAMESPACE) + 1)).'.php';
        if (file_exists($path))
            include_once $path;
    } else {
        $path = __DIR__.'/../Packages/'.str_replace("\\", "/", $class_name).'.php';
        if (file_exists($path))
            include_once $path;
    }
});
