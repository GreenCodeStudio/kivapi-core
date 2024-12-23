<?php

namespace Core\Package\CLI;

use MKrawczyk\FunQuery\FunQuery;

class PackageListAvailableCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{
    public function execute()
    {

        $package = new \Core\Package\Package();
        var_dump(FunQuery::from($package->listAvailablePackages()));
    }

}
