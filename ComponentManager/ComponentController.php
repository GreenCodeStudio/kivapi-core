<?php

namespace Core\ComponentManager;

use MKrawczyk\Mpts\Environment;
use MKrawczyk\Mpts\Parser\XMLParser;

abstract class ComponentController extends BaseComponentController
{
    public static function type()
    {
        return "component";
    }

    public abstract function loadView();
    public function loadMPTS(string $fileName){
        $template = XMLParser::Parse(file_get_contents($fileName));
        $env = new Environment();
        $env->variables = $this->parameters;
        echo $template->executeToString($env);
    }

    public function getInitInfo()
    {
        $className = static ::class;
        $exploded = explode('\\', $className);
        $lastBackslash = strrpos($className, "\\");
        if ($exploded[0] == MAIN_NAMESPACE)
            yield (object)['module' => null, 'component' => $exploded[count($exploded) - 2]];
        else
            yield (object)['module' => $exploded[0] . '\\' . $exploded[1], 'component' => $exploded[3]];

        if (!empty($this->subRouteComponent)) {
            foreach ($this->subRouteComponent->getInitInfo() as $item)
                yield $item;
        }
    }
}
