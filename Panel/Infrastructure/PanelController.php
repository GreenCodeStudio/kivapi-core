<?php
namespace Core\Panel\Infrastructure;

use Core\Panel\Authorization\Exceptions\NoPermissionException;
use Core\Panel\Authorization\Authorization;

abstract class PanelController
{
    public $initInfo;

    public function __construct()
    {
        $this->initInfo = new \stdClass();
    }

    public function preAction()
    {

    }

    public function postAction()
    {

    }

    public function getInitInfo()
    {
        return $this->initInfo;
    }

    public function isDebug()
    {
        return ($_ENV['debug'] ?? '') == 'true';
    }

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

    public function redirect(string $url)
    {
        http_response_code(301);
        header("location: $url");
    }


}