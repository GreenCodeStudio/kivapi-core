<?php


namespace Core\Panel\Page\StandardControllers;


use Core\ComponentManager\ComponentManager;
use Core\ComponentManager\PageRepository;
use Core\ComponentManager\SpecialComponents\PlaceholderComponent;
use Core\Panel\Infrastructure\PanelStandardController;
use Core\Routing\ParameterParser;
use Core\Routing\RouteNode;
use MKrawczyk\FunQuery\FunQuery;

class PageSimulatorStandardController extends PanelStandardController
{
    public function index()
    {
    }

    public function postAction()
    {
        $data = json_decode($_POST['data']);
        $node = $data;

        if ($data->showParents && !empty($data->parent_id))
            $node->parent = (new PageRepository())->getAll()[$data->parent_id];
        else
            $node->parent = null;

        $nodes = [];
        while ($node) {
            $nodes[] = $node;
            $node = $node->parent ?? null;
        }
        $nodes = array_reverse($nodes);

        $routeNodes = FunQuery::create($nodes)->map(fn($node) => new RouteNode($node,[]))->toArray();
        $controllers = FunQuery::create($routeNodes)
            ->map(fn($routeNode) => ComponentManager::findController($routeNode->node->module, $routeNode->node->component,  [], $routeNode->node))
            ->toArray();
        $component = $controllers[0];
        foreach ($controllers as $i => $controller) {
            $controller->subRouteComponent = $controllers[$i + 1] ?? new PlaceholderComponent();
        }
        $trackingCodes = [];
        include __DIR__.'/../../../../Core/BaseHTML.php';
    }
}
