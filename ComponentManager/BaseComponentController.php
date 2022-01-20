<?php

namespace Core\ComponentManager;

abstract class BaseComponentController
{
    public abstract static function type();

    public function fillMetadata(object $meta)
    {
    }
}