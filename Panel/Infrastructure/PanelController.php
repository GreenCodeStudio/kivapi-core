<?php
namespace Core\Panel\Infrastructure;

use Core\Panel\Authorization\Exceptions\NoPermissionException;
use Core\Panel\Authorization\Authorization;

abstract class PanelController extends Controller
{

    public function hasPermission()
    {
        return Authorization::isLogged();
    }

    /**
     * @throws NoPermissionException
     */
    public function will(string $group, string $permission)
    {
        if (!$this->can($group, $permission))
            throw new NoPermissionException();
    }

    public function can(string $group, string $permission): bool
    {
        return true;
        //return \Authorization\Authorization::getUserData()->permissions->can($group, $permission);
    }

}