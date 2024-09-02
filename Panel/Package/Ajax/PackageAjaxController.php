<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 14.07.2018
 * Time: 13:51
 */

namespace Core\Panel\Package\Ajax;

use Core\Panel\Authorization\Exceptions\UnauthorizedException;
use Core\Panel\Infrastructure\PanelAjaxController;
use Core\Package\Package;

class PackageAjaxController extends PanelAjaxController
{
    public function getTable($options)
    {
        $this->will('package', 'show');
        $package = new Package();
        return $package->getDataTable($options);
    }
    public function getAvailableTable($options)
    {
        $this->will('package', 'show');
        $package = new Package();
        return $package->getAvailableDataTable($options);
    }

    public function prepareInstallation(string $url)
    {
        if (getenv('allowPackageInstall') ?? '' != 'true')
            throw new UnauthorizedException();

        return (new Package())->prepareInstallation($url);
    }

    public function install(string $tmpID, string $url)
    {
        if (getenv('allowPackageInstall') ?? '' != 'true')
            throw new UnauthorizedException();

        return (new Package())->install($tmpID, $url);
    }

}
