<?php


namespace Core\Panel\Page\StandardControllers;


use Core\ComponentManager\ComponentManager;
use Core\Panel\Infrastructure\PanelStandardController;
use Core\Routing\ParameterParser;

class PageSimulatorStandardController extends PanelStandardController
{
    public function index()
    {
    }

    public function postAction()
    {
        $data = json_decode($_POST['data']);
        $paramsParsed = (new ParameterParser([]))->parseParamStruct($data->parameters);
        $component = ComponentManager::findController($data->module, $data->component, $paramsParsed);
        include __DIR__.'/../../../../Core/BaseHTML.php';
    }
}