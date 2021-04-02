<?php

namespace User\Ajax;

use Core\AjaxController;

class Token extends AjaxController
{
    public function getTable($options)
    {
        $this->will('user', 'showToken');
        $Token = new \User\Token();
        return $Token->getDataTable($options);
    }

    public function insert($data)
    {
        $this->will('user', 'addToken');
        $Token = new \User\Token();
        $Token->insert($data);
    }
}