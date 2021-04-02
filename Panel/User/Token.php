<?php

namespace User;

use Core\BussinesLogic;
use Core\WebSocket\Sender;
use DateTime;
use User\Repository\TokenRepository;

class Token extends BussinesLogic
{
    public function __construct()
    {
        $this->defaultDB = new TokenRepository();
    }

    public function getDataTable($options)
    {
        return $this->defaultDB->getDataTable($options);
    }

    public function update(int $id, $data)
    {
        $filtered = $this->filterData($data);
        $this->defaultDB->update($id, $filtered);
        Sender::sendToUsers(["User", "Token", "Update", $id]);
    }

    protected function filterData($data)
    {

        $filtered['isOnce'] = (int)isset($data->isOnce);
        $filtered['type'] = $data->type;
        $filtered['id_user'] = $data->id_user;

        return $filtered;
    }

    public function insert($data)
    {
        $now = (new DateTime());
        $filtered = $this->filterData($data);
        $filtered['token'] = bin2hex(openssl_random_pseudo_bytes(16));
        $filtered['created'] = $now;
        $filtered['expire'] = null;
        $id = $this->defaultDB->insert($filtered);
        Sender::sendToUsers(["User", "Token", "Insert", $id]);
    }

    public function getSelects()
    {
        $ret = [];
        $user = new Repository\UserRepository();
        $ret["user"] = $user->getSelect();
        return $ret;
    }
}