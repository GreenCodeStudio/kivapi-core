<?php

namespace Core\Package\CLI;

class PackageListCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{

    public function execute()
    {
        $package=new \Core\Package\Package();
        return iterator_to_array($package->listAllPackages());
    }
}