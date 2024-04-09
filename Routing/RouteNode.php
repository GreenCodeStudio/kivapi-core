<?php


namespace Core\Routing;


class RouteNode
{
    public function __construct($node, $query)
    {
        $this->node = $node;
        $this->query = $query;
    }
}
