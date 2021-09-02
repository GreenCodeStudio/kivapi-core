<?php

namespace Core\Panel\User\CLI;

use Core\Panel\User\User;
use stdClass;

class UserAddCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{
    public static function getParameters(): array
    {
        return [
            'name' => (object)['type' => 'string', 'required' => true],
            'surname' => (object)['type' => 'string', 'required' => true],
            'mail' => (object)['type' => 'string', 'required' => true],
            'password' => (object)['type' => 'string', 'required' => true],
            'password2' => (object)['type' => 'string', 'required' => true],
        ];
    }

    public function execute()
    {
        $user = new User();
        $data = new stdClass();
        $data->name = $this->parameters->name;
        $data->surname = $this->parameters->surname;
        $data->mail = $this->parameters->mail;
        $data->password = $this->parameters->password;
        $data->password2 = $this->parameters->password2;
        $data->permission = [];
        $user->insert($data);
    }
}