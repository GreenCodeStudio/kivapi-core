<?php
namespace Core\Panel\User\CLI;
class UserCommand extends \Kivapi\KivapiCli\Commands\AbstractCommandGroup
{
    function getSubCommands(): array
    {
        return ['Add' => UserAddCommand::class];
    }
}