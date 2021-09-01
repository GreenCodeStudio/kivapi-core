<?php

namespace Core\Routing;

use Core\ComponentManager\ComponentManager;
use Core\ComponentManager\PageRepository;
use Core\ComponentManager\RedirectionController;
use Core\ComponentManager\SpecialComponents\EmptyComponent;
use Core\Exceptions\NotFoundException;
use Core\TrackingCode\TrackingCode;
use MKrawczyk\FunQuery\FunQuery;

class ComponentRouter extends Router
{
    public $url;
    private array $query = [];

    public function findController()
    {
        $this->parseUrl();
        $nodes = $this->findRoute();
        $this->routeNodes = FunQuery::create($nodes)->map(fn($node) => new RouteNode($node, (new ParameterParser($this->query))->findParameters($node)))->toArray();
        $controllers = FunQuery::create($this->routeNodes)->map(fn($routeNode) => ComponentManager::findController($routeNode->node->module, $routeNode->node->component, $routeNode->parameters))->toArray();
        $this->controller = $controllers[0];
        foreach ($controllers as $i => $controller) {
            $controller->subRouteComponent = $controllers[$i + 1] ?? new EmptyComponent();
        }
    }

    protected function parseUrl()
    {
        $posQueryStart = strpos($this->url, '?');
        if ($posQueryStart === false)
            $this->urlWithoutQuery = $this->url;
        else {
            $this->urlWithoutQuery = substr($this->url, 0, $posQueryStart);
            $queryString = substr($this->url, $posQueryStart + 1);
            foreach (explode('&', $queryString) as $pair) {
                list($key, $value) = explode('=', $pair);
                $this->query[urldecode($key)] = urldecode($value);
            }
        }
    }

    private function findRoute()
    {
        $node = $this->findRouteMainNode();
        $ret = [];
        while ($node) {
            $ret[] = $node;
            $node = $node->parent ?? null;
        }
        return array_reverse($ret);
    }

    private function findRouteMainNode()
    {
        $all = (new PageRepository())->getAll();
        foreach ($all as $node) {
            if (!empty($node->path) && ($node->path == $this->urlWithoutQuery || rtrim($node->path, '/') == rtrim($this->urlWithoutQuery, '/')))
                return $node;
        }
        throw new NotFoundException("not found path");
    }

    public function invoke()
    {
        $component = $this->controller;
        if ($component instanceof RedirectionController) {
            header("Location: " . $component->execute());
        } else {
            $meta = (object)['title' => $this->lastValue('title'), 'description' => $this->lastValue('description')];
            $path = $this->lastValue('path');
            $urlPrefix = $_ENV['urlPrefix'];
            if (!empty($path) && !empty($urlPrefix)) {
                $meta->canonical = $urlPrefix . $path;
            }
            $trackingCodes = (new TrackingCode())->getActiveCodes();
            include __DIR__ . '/../BaseHTML.php';
        }
    }

    private function lastValue($field)
    {
        $ret = null;
        foreach ($this->routeNodes as $node) {
            $v = $node->node->$field;
            if (!empty($v)) {
                $ret = $v;
            }
        }
        return $ret;
    }

    public function sendBackSuccess()
    {

    }

    public function sendBackError()
    {

    }
}