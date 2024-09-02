<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 14.07.2018
 * Time: 13:51
 */

namespace Core\Panel\Component\Ajax;

use Core\ComponentManager\ComponentManager;
use Core\Panel\Authorization\Exceptions\UnauthorizedException;
use Core\Panel\Infrastructure\PanelAjaxController;
use Core\Package\Package;

class ComponentAjaxController extends PanelAjaxController
{
    public function getTable($options)
    {
        $this->will('component', 'show');
        return ComponentManager::getDataTable($options);
    }
}
