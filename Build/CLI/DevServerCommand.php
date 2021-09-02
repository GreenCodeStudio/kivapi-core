<?php

namespace Core\Build\CLI;
class DevServerCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{
    public function execute()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $dir = __DIR__ . "\..\..\..\Public";
            system("cd $dir && php -S 0.0.0.0:8080 ./devRouter.php");
        } else {
            $dir = __DIR__ . "/../../../Public";
            system("cd $dir && php -S 0.0.0.0:8080 ./devRouter.php");
        }
    }
}