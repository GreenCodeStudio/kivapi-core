<?php

namespace Core\Panel\Package\StandardControllers;


use Core\Exceptions\NotFoundException;
use Core\Panel\Authorization\Authorization;
use Core\Panel\Authorization\Permissions;
use Core\Panel\Infrastructure\PanelStandardController;
use Core\Panel\User\User;

class PackageStandardController extends PanelStandardController
{
    function index()
    {
        $this->will('package', 'show');
        $this->addView('Package', 'list');
        $this->pushBreadcrumb(['title' => t("Core.Panel.Package.Packages"), 'url' => '/Package']);
    }
}
