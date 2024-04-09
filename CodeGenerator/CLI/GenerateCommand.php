<?php

namespace Core\CodeGenerator\CLI;
class GenerateCommand extends \Kivapi\KivapiCli\Commands\AbstractCommandGroup
{
    function getSubCommands(): array
    {
        return ['Component' => GenerateComponentCommand::class];
    }
}
