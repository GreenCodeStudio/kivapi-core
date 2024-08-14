<?php

namespace Core\ComponentManager;

abstract class BaseComponentController
{
    public $subRouteComponent=null;
    public $params=null;
    public abstract static function type();

    public function fillMetadata(object $meta)
    {
    }
}
