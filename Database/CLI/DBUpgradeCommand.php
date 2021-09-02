<?php

namespace Core\Database\CLI;

class DBUpgradeCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{

    public function execute()
    {
        $migration=new \Core\Database\Migration();
        $migration->upgrade();
        $migration->execute();
    }
}