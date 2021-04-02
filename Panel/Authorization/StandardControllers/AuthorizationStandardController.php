<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 19.07.2018
 * Time: 10:20
 */

namespace Core\Panel\Authorization\StandardControllers;


use Core\Panel\Authorization\Exceptions\BadAuthorizationException;
use Core\Panel\Authorization\Exceptions\ExpiredTokenException;
use Core\Panel\Infrastructure\PanelStandardController;

class AuthorizationStandardController extends PanelStandardController
{
    public function index()
    {
        $this->addView('Authorization', 'login');
    }

    /**
     * @throws BadAuthorizationException
     * @throws ExpiredTokenException
     */
    public function token(string $token)
    {
        \Authorization\Authorization::loginByToken($token);
        header('Location:/');
        http_response_code(302);
    }

    public function postAction()
    {
        require __DIR__.'/../Views/loginTemplate.php';
    }

    public function hasPermission()
    {
        return true;
    }
}