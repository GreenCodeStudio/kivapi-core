<?php

namespace Core\Panel\User\Repository;

use Core\Database\DB;
use Core\Repository;
use stdClass;


class TokenRepository extends Repository
{

    public function __construct()
    {
        $this->archiveMode = static::ArchiveMode_OnlyExisting;
    }

    public function defaultTable(): string
    {
        return "token";
    }

    public function getDataTable($options)
    {
        $start = (int)$options->start;
        $limit = (int)$options->limit;
        $sqlOrder = $this->getOrderSQL($options);
        $rows = DB::get("SELECT * FROM token $sqlOrder LIMIT $start,$limit");
        $total = DB::get("SELECT count(*) as count FROM token")[0]->count;
        return ['rows' => $rows, 'total' => $total];
    }

    private function getOrderSQL($options)
    {
        if (empty($options->sort))
            return "";
        else {
            $mapping = ['token' => 'token', 'type' => 'type', 'id_user' => 'id_user', 'created' => 'created', 'expire' => 'expire', 'isOnce' => 'isOnce'];
            if (empty($mapping[$options->sort->col]))
                throw new \Exception();
            return ' ORDER BY '.DB::safeKey($mapping[$options->sort->col]).' '.($options->sort->desc ? 'DESC' : 'ASC').' ';
        }
    }

    public function getTokenWithUser(string $token)
    {
        $tokens = DB::get("SELECT t.id, t.token, t.type, t.expire,u.id as user_id,u.mail as user_mail,u.name as user_name,u.surname as user_surname
        FROM token t 
        LEFT JOIN user u on t.id_user = u.id
        WHERE t.token=?", [$token]);
        if (empty($tokens)) return null;
        $tokens[0]->user = new stdClass();
        $tokens[0]->user->id = $tokens[0]->user_id;
        $tokens[0]->user->mail = $tokens[0]->user_mail;
        $tokens[0]->user->name = $tokens[0]->user_name;
        $tokens[0]->user->surname = $tokens[0]->user_surname;
        return $tokens[0];
    }
}