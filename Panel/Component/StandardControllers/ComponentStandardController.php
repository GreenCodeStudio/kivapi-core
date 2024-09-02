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
        $this->pushBreadcrumb(['title' => t("Core.Panel.Component.Components"), 'url' => '/Component']);
    }

    function details(string $package, string $name)
    {
        if(empty($package))
            $package = null;

        $item = ((new ComponentManager())->getDeveloperDetails($package, $name));
        dump($item);
        if($item==null)
            throw new NotFoundException();
        $this->addView('Component', 'details', ['item' => $item]);
        $this->pushBreadcrumb(['title' => t("Core.Panel.Component.Components"), 'url' => '/Component']);
    }
}
