<?php


namespace Core\Routing;


class RouteNode
{
    /**
     * @var mixed
     */
    public $node;
    /**
     * @var mixed
     */
    public $query;

    public function __construct($node, $query)
    {
        $this->node = $node;
        $this->query = $query;
    }
}
