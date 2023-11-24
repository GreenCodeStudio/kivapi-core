<?php

namespace Core\ComponentManager;

class ComponentManager
{
    public static function findController(?string $package, string $name, object $params)
    {
        $className = static::findControllerClass($package, $name);
        $controller = new $className($params);
        return $controller;
    }

    public static function findControllerClass(?string $package, string $name)
    {
        if ($package === null) {
            $dir = __DIR__.'/../../Components/'.$name.'/';
            if (!is_dir($dir)) throw new \Exception("Component don't exists");
            if (is_file($dir.'Controller.php')) {
                $className = MAIN_NAMESPACE.'\\Components\\'.$name.'\\Controller';
                return $className;
            }
        } else {
            $dir = __DIR__.'/../../Packages/'.str_replace('\\', '/', $package).'/Components/'.$name.'/';
            if (!is_dir($dir)) throw new \Exception("Component don't exists");
            if (is_file($dir.'Controller.php')) {
                $className = $package.'\\Components\\'.$name.'\\Controller';
                return $className;
            }
        }
    }

    public static function listComponents()
    {
        $ret = [];
        $dir = __DIR__.'/../../Components/';
        foreach (scandir($dir) as $name) {
            if ($name != '.' && $name != '..' && is_dir($dir.$name)) {
                $ret[] = [null, $name];
            }
        }

        $dir = __DIR__.'/../../Packages/';
        if (is_dir($dir)) {
            foreach (scandir($dir) as $packageGroup) {
                if ($packageGroup != '.' && $packageGroup != '..' && is_dir($dir.$packageGroup)) {
                    foreach (scandir($dir.$packageGroup) as $package) {
                        if ($package != '.' && $package != '..' && is_dir($dir.$packageGroup.'/'.$package)) {
                            $subdir = $dir.$packageGroup.'/'.$package.'/Components/';
                            foreach (scandir($subdir) as $name) {
                                if ($name != '.' && $name != '..' && is_dir($subdir.$name)) {
                                    $ret[] = [$packageGroup.'\\'.$package, $name];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $ret;
    }
}
