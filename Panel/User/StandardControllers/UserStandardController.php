<?php

namespace Core\Panel\User\StandardControllers;


use Core\Exceptions\NotFoundException;
use Core\Panel\Authorization\Authorization;
use Core\Panel\Authorization\Permissions;
use Core\Panel\Infrastructure\PanelStandardController;
use Core\Panel\User\User;

class UserStandardController extends PanelStandardController
{

    function index()
    {
        $this->will('user', 'show');
        $this->addView('User', 'list');
        $this->pushBreadcrumb(['title' => 'Użytkownicy', 'url' => '/User']);

    }

    function edit(int $id)
    {
        $this->will('user', 'edit');
        $permissionsStructure = Permissions::readStructure();
        $this->addView('User', 'edit', ['type' => 'edit', 'permissionsStructure' => $permissionsStructure]);
        $this->pushBreadcrumb(['title' => 'Użytkownicy', 'url' => 'User']);
        $this->pushBreadcrumb(['title' => 'Edycja', 'url' => 'User/edit/'.$id]);
    }

    function edit_data(int $id)
    {
        $this->will('user', 'edit');
        $user = new User();
        $data = $user->getById($id);
        if ($data == null)
            throw new NotFoundException();
        $data->permission = $user->getPermissions($id);
        return ['user' => $data];
    }

    function add()
    {
        $this->will('user', 'add');
        $permissionsStructure = Permissions::readStructure();
        $this->addView('User', 'edit', ['type' => 'add', 'permissionsStructure' => $permissionsStructure]);
        $this->pushBreadcrumb(['title' => 'Użytkownicy', 'url' => 'User']);
        $this->pushBreadcrumb(['title' => 'Dodaj', 'url' => 'User/add']);
    }

    function myAccount()
    {
        $this->pushBreadcrumb(['title' => 'Moje konto', 'url' => 'User/myAccount']);
        $user = ( new User())->getById(Authorization::getUserId());
        $this->addView('User', 'myAccount', ['user' => $user]);
    }
}
