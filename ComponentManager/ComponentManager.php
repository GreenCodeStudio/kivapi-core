<?php

namespace Core\ComponentManager;

use Core\Routing\ParameterParser;
use MKrawczyk\FunQuery\FunQuery;

class ComponentManager
{
    public static function loadControllerWithParams(?string $package, string $name, array $query, object $node, bool $inSiteEdit = false)
    {
        $className = static::findControllerClass($package, $name);
        $parser=(new ParameterParser($query, $inSiteEdit));
        $params = $parser->findParameters($className::DefinedParameters(), $node);
        $controller = new $className($params);
        $controller->subParamComponents = $parser->subComponents;
        return $controller;
    }

    public static function loadController(?string $package, string $name, $params)
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
        if(is_dir($dir)) {
            foreach (scandir($dir) as $name) {
                if ($name != '.' && $name != '..' && is_dir($dir.$name)) {
                    $ret[] = [null, $name];
                }
            }
        }

        $dir = __DIR__.'/../../Packages/';
        if (is_dir($dir)) {
            foreach (scandir($dir) as $packageGroup) {
                if ($packageGroup != '.' && $packageGroup != '..' && is_dir($dir.$packageGroup)) {
                    foreach (scandir($dir.$packageGroup) as $package) {
                        if ($package != '.' && $package != '..' && is_dir($dir.$packageGroup.'/'.$package)) {
                            $subdir = $dir.$packageGroup.'/'.$package.'/Components/';
                            if (is_dir($subdir)) {
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
        }
        return $ret;
    }
    public static function listComponentsWithDefs(){
        $ret = [];
        foreach (static::listComponents() as $component) {
            $package = $component[0];
            $name = $component[1];
            $className=static::findControllerClass($package, $name);
            $definedParameters=$className::DefinedParameters();
            $ret[] = (object)[
                'package' => $component[0],
                'name' => $component[1],
                'definedParameters' => $definedParameters
            ];
        }
        return $ret;
    }
    public static function getDeveloperDetails(?string $package, string $name)
    {
        $className = static::findControllerClass($package, $name);
        $definedParameters = $className::DefinedParameters();
        return (object)[
            'package' => $package,
            'name' => $name,
            'definedParameters' => $definedParameters
        ];
    }

    public static function getDataTable($options)
    {
        $rows = self::listComponents();
        return ['rows' => FunQuery::create($rows)->map(fn($x)=>['package'=>$x[0], 'name'=>$x[1]]), 'total' => count($rows)];
    }
}
