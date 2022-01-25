<?php

namespace Core\Package\CLI;

class PackageInstallCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{
    public static function getParameters(): array
    {
        return ['url' => 'string'];
    }

    public function execute()
    {
        $package = new \Core\Package\Package();
        $package->prepareInstallation($this->parameters->url);
    }
}