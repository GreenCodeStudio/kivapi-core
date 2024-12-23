<?php

namespace Core\Package\CLI;

use Core\Package\Package;

class PackageInstallCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{
    public static function getParameters(): array
    {
        return ['url' => 'string'];
    }

    public function execute()
    {
        $package = new \Core\Package\Package();
        $info = $package->prepareInstallation($this->parameters->url);

        return $package->install($info['tmpId'], $this->parameters->url);
    }
}
