<?php

namespace User\Console;

use Core\AbstractController;
use stdClass;

class User extends AbstractController
{

    function add(string $name, string $surname, string $mail, string $password)
    {
        $user = new \User\User();
        $data = new stdClass();
        $data->name = $name;
        $data->surname = $surname;
        $data->mail = $mail;
        $data->password = $data->password2 = $password;
        $data->permission = [];
        $user->insert($data);
    }
    function get(){
        $user = new \User\User();
        return $user->getAll();
    }
    function addPermssion(int $idUser, string $group, string $name){

        $user = new \User\User();
        $user->addPermission($idUser, $group, $name);
    }
}
