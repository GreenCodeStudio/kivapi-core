<?php

namespace Core\ComponentManager;

use MKrawczyk\Mpts\Environment;
use MKrawczyk\Mpts\Parser\XMLParser;

abstract class ComponentController extends BaseComponentController
{
    protected $instanceId;

    public function __construct()
    {
        $this->instanceId = uniqid();
    }

    public static function type()
    {
        return "component";
    }

    public abstract function loadView();

    public function getView()
    {
        ob_start();
        $this->loadView();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function loadMPTS(string $fileName)
    {
        $template = XMLParser::Parse(file_get_contents($fileName));
        $env = new Environment();
        $env->variables = (array)$this;
        $env->variables['dump'] = function ($x) {
            return print_r($x, true);
        };
        $result = $template->execute($env);
        if ($result instanceof \DOMDocumentFragment) {
            if ($result->firstElementChild) {
                if ($this->instanceId) {
                    $result->firstElementChild->setAttribute('data-instance-id', $this->instanceId);
                }
                $result->firstElementChild->setAttribute('data-component', implode("\\", $this->getCurrentComponentName()));
            }
        }
        echo $env->document->saveXML($result);
    }

    private function getCurrentComponentName()
    {

        $className = static ::class;
        $exploded = explode('\\', $className);

        if ($exploded[0] == MAIN_NAMESPACE) {
            return [null,
                $exploded[count($exploded) - 2]];
        } else {
            return [$exploded[0].'\\'.$exploded[1], $exploded[3]];
        }
    }

    public function getInitInfo()
    {
        $ret = new \stdClass();
        $ret->instanceId = $this->instanceId;
        $ret->frontendData = $this->getFrontendData();


        [$ret->module, $ret->component] = $this->getCurrentComponentName();

        yield $ret;

        if (!empty($this->subRouteComponent)) {
            foreach ($this->subRouteComponent->getInitInfo() as $item)
                yield $item;
        }
        foreach ($this->subParamComponents as $component) {
            foreach ($component->getInitInfo() as $item)
                yield $item;
        }
    }

    public function getViewHtml()
    {
        ob_start();
        $this->loadView();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function getFrontendData()
    {
        return null;
    }

    public static function DefinedParameters()
    {
        return [];
    }
}
