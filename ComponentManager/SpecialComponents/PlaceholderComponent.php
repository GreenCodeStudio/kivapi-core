<?php

namespace Core\ComponentManager\SpecialComponents;

use Core\ComponentManager\ComponentController;

class PlaceholderComponent extends ComponentController
{

    public function loadView()
    {
        echo '<div>Place for component</div>';
    }
}
