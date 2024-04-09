<?php

namespace Core\ComponentManager;

abstract class RedirectionController extends BaseComponentController
{
    public abstract function execute();

    public static function type()
    {
        return "redirection";
    }
}
