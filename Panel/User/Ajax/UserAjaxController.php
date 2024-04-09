<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 14.07.2018
 * Time: 13:51
 */

namespace Core\Panel\User\Ajax;

use Core\Panel\Authorization\Authorization;
use Core\Panel\Infrastructure\PanelAjaxController;
use Core\Panel\User\User;

class UserAjaxController extends PanelAjaxController
{
    public function getTable($options)
    {
        $this->will('user', 'show');
        $user = new User();
        return $user->getDataTable($options);
    }

    public function update($data)
    {
        $this->will('user', 'edit');
        $user = new User();
        $user->update($data->id, $data);
    }

    public function insert($data)
    {
        $this->will('user', 'add');
        $user = new User();
        $user->insert($data);
    }

    public function changeCurrentUserPassword(string $password, string $password2)
    {
        if ($password !== $password2)
            throw new \InvalidArgumentException("Passwords not identical");

        (new \User\User())->changePassword(Authorization::getUserId(), $password);
    }
}
