<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 19.07.2018
 * Time: 10:21
 */

namespace Core\Panel\Authorization;

use Core\Panel\Authorization\Exceptions\BadAuthorizationException;
use Core\Panel\Authorization\Exceptions\ExpiredTokenException;
use Core\Panel\Authorization\Repository\AuthorizationRepository;
use Core\Panel\User\Repository\TokenRepository;
use Core\Panel\User\Repository\UserRepository;

class Authorization
{
    const salt = 'l(vu$bL2';
    static private $userData = null;
    static private $isUserDataRead = false;


    static public function login(string $username, string $password)
    {
        $userRepository = new UserRepository();
        $userData = $userRepository->getByUsername($username, true);
        if (!empty($userData)) {
            if (self::checkPassword($userData, $password)) {
                self::executeLogin($userData);
            } else {
                throw new Exceptions\BadAuthorizationException();
            }
        } else {
            throw new Exceptions\BadAuthorizationException();
        }
    }

    private static function checkPassword($userData, $password)
    {
        return $userData->password === self::hashPassword($password, $userData->salt);
    }

    public static function hashPassword(string $password, string $salt)
    {
        return hash('sha512', hash('sha512', $password).$salt.static::salt);
    }

    public static function executeLogin($userData): void
    {
        unset($userData->salt);
        unset($userData->password);
        $token = static::generateToken();
        $userData->permissions = new Permissions($userData->id);
        (new AuthorizationRepository())->Insert($token, $userData);
        setcookie('login', $token, (int)(time() * 2), '/');
    }

    private static function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    /**
     * @throws BadAuthorizationException
     * @throws ExpiredTokenException
     */
    static public function loginByToken(string $token)
    {
        $tokenRepository = new TokenRepository();
        $item = $tokenRepository->getTokenWithUser($token);
        if (empty($item) || $item->type != 'login')
            throw new BadAuthorizationException();
        if (!empty($item->expire) && strtotime($item->expire) < time())
            throw new ExpiredTokenException();
        self::executeLogin($item->user);
    }

    public static function generateSalt()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    static public function isLogged()
    {
        return !empty(self::getUserData());
    }

    static public function getUserData()
    {
        if (!self::$isUserDataRead) {
            if (empty($_COOKIE['login']))
                return null;

            $token = $_COOKIE['login'];
            self::$userData = (new AuthorizationRepository())->Get($token);
            self::$isUserDataRead = true;
        }
        return self::$userData;
    }

    static public function getUserId()
    {
        return self::getUserData()->id;
    }

    public static function logout()
    {
        if (!empty($_COOKIE['login'])) {
            $token = $_COOKIE['login'];
            (new AuthorizationRepository())->Delete($token);
        }
        self::$userData = null;
        self::$isUserDataRead = true;
        setcookie('login', NULL, 0, '/');
    }
}