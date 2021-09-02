<?php

namespace Core\Database\CLI;
class DBCommand extends \Kivapi\KivapiCli\Commands\AbstractCommandGroup
{
    function getSubCommands(): array
    {
        return ['Upgrade' => DBUpgradeCommand::class];
    }
}