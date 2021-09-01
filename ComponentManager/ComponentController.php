<?php

namespace Core\ComponentManager;

abstract class ComponentController extends BaseComponentController
{
    public static function type()
    {
        return "component";
    }

    public abstract function loadView();
}