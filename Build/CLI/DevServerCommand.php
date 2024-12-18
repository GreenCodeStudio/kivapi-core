<?php

namespace Core\Build\CLI;
class DevServerCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{
    public function execute()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $dir = __DIR__."\..\..\..\Public";
            chdir($dir);
        } else {
            $dir = __DIR__."/../../../Public";
            chdir($dir);
        }
        system("php -d post_max_size=128G -d upload_max_filesize=128G -S 0.0.0.0:8000 ./devRouter.php");
    }
}
