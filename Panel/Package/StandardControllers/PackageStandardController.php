<?php

namespace Core\Panel\Package\StandardControllers;


use Core\Exceptions\NotFoundException;
use Core\Panel\Authorization\Authorization;
use Core\Panel\Authorization\Exceptions\UnauthorizedException;
use Core\Panel\Authorization\Permissions;
use Core\Panel\Infrastructure\PanelStandardController;
use Core\Package\Package;
use Core\Panel\User\User;

class PackageStandardController extends PanelStandardController
{
    function index()
    {
        $this->will('package', 'show');
        $this->addView('Package', 'list');
        $this->pushBreadcrumb(['title' => t("Core.Panel.Package.Packages"), 'url' => '/Package']);
    }
    function available()
    {
        $this->will('package', 'install');
        $this->addView('Package', 'availableList');
        $this->pushBreadcrumb(['title' => t("Core.Panel.Package.Packages"), 'url' => '/Package']);
    }

    function details(string $vendor, string $name)
    {
        $item = ((new Package())->getPackageDetails($vendor, $name));
        if($item==null)
            throw new NotFoundException();
        $this->addView('Package', 'details', ['item' => $item]);
        $this->pushBreadcrumb(['title' => t("Core.Panel.Package.Packages"), 'url' => '/Package']);
    }
    function availableDetails(string $vendor, string $name)
    {
        $item = ((new Package())->getAvailablePackageDetails($vendor, $name));
        if($item==null)
            throw new NotFoundException();
        $this->addView('Package', 'details', ['item' => $item]);
        $this->pushBreadcrumb(['title' => t("Core.Panel.Package.Packages"), 'url' => '/Package']);
        $this->setData($item);
    }

    function install()
    {
        if (getenv('allowPackageInstall') ?? '' != 'true')
            throw new UnauthorizedException();
        $this->addView('Package', 'install');
        $this->pushBreadcrumb(['title' => t("Core.Panel.Package.Packages"), 'url' => '/Package']);
    }
}
