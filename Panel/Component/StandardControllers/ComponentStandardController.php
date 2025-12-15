<?php

namespace Core\Panel\Component\StandardControllers;


use Core\ComponentManager\ComponentManager;
use Core\Exceptions\NotFoundException;
use Core\Panel\Authorization\Authorization;
use Core\Panel\Authorization\Exceptions\UnauthorizedException;
use Core\Panel\Authorization\Permissions;
use Core\Panel\Infrastructure\PanelStandardController;
use Core\Package\Package;
use Core\Panel\User\User;

class ComponentStandardController extends PanelStandardController
{
    function index()
    {
        $this->will('component', 'show');
        $this->addView('Component', 'list');
        $this->pushBreadcrumb(['title' => t("Core.Panel.Component.Components"), 'url' => '/panel/Component']);
    }

    function details(string ...$args)
    {
        if (count($args) == 3) {
            $package = $args[0].'\\'.$args[1];
            $name = $args[2];
        } else {
            $package = null;
            $name = $args[0];
        }

        $item = ((new ComponentManager())->getDeveloperDetails($package, $name));
        if ($item == null)
            throw new NotFoundException();
        $this->addView('Component', 'details', ['item' => $item]);
        $this->pushBreadcrumb(['title' => t("Core.Panel.Component.Components"), 'url' => '/panel/Component']);
    }
}
