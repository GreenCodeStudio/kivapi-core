<?php


namespace Core\Routing;


use Core\ComponentManager\PageRepository;

class RouteHelper
{
    public function reverseRoute($component, $module)
    {
        return (new PageRepository())->reverseRoute($component, $module);
    }
}