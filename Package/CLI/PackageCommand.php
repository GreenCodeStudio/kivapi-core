<?php

namespace Core\Package\CLI;


class PackageCommand extends \Kivapi\KivapiCli\Commands\AbstractCommandGroup
{
    function getSubCommands(): array
    {
        return ['List' => PackageListCommand::class, 'Install' => PackageInstallCommand::class];
    }
}