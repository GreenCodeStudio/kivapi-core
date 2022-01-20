<?php

namespace Core\Panel\Infrastructure;

use Core\Panel\Authorization\Authorization;
use Core\Panel\Authorization\Exceptions\NoPermissionException;

abstract class Controller
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
        return true;
    }

    public function redirect(string $url)
    {
        http_response_code(301);
        header("location: $url");
    }

}