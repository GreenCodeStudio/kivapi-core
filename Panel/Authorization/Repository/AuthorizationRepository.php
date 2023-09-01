<?php


namespace Core\Panel\Authorization\Repository;


use Core\Database\MiniDB;

class AuthorizationRepository
{
    public function Insert(string $token, $userData)
    {
        MiniDB::GetConnection()->setEx('token_'.$_ENV['prefix'].$token, $this->GetExpirationSeconds(), serialize($userData));
    }

    protected function GetExpirationSeconds()
    {
        return 60 * 24 * 3600;
    }

    public function Get(string $token)
    {
        $connection = MiniDB::GetConnection();
        $prefix = $_ENV['prefix'] ?? '';
        $dataSerialized = $connection->get('token_'.$prefix.$token);

        if ($dataSerialized === false)
            return null;
        else {
            $connection->expire('token_'.$prefix.$token, $this->GetExpirationSeconds());
            return unserialize($dataSerialized);
        }
    }

    public function Delete(string $token)
    {
        MiniDB::GetConnection()->del('token_'.$_ENV['prefix'].$token);
    }
}