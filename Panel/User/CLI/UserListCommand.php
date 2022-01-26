<?php

namespace Core\Panel\User\CLI;

use Core\Panel\User\User;
use stdClass;

class UserListCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{

    public function execute()
    {
        $user = new User();
        return $user->getAll();
    }
}