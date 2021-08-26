<?php

namespace Core\ComponentManager\SpecialComponents;
use Core\ComponentManager\ComponentController;

class EmptyComponent extends ComponentController
{

    public function loadView()
    {
        //empty by design
    }
}