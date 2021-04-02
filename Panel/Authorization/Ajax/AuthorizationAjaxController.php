<?php

namespace Core\Panel\Authorization\Ajax;

use Core\Panel\Infrastructure\PanelAjaxController;

class AuthorizationAjaxController extends PanelAjaxController
{
    /**
     * @throws BadAuthorizationException
     */
    public function login(string $username, string $password)
    {
        \Core\Panel\Authorization\Authorization::login($username, $password);
    }

    public function logout()
    {
        \Core\Panel\Authorization\Authorization::logout();
    }

    public function hasPermission()
    {
        return true;
    }
}