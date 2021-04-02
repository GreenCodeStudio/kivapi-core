<?php


namespace Core\Routing;


class RouteNode
{
    public function __construct($node, $parameters)
    {
        $this->node = $node;
        $this->parameters = $parameters;
    }
}