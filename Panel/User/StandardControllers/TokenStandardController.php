<?php

namespace Core\Panel\User\StandardControllers;

use Authorization\Permissions;
use Common\PageStandardController;
use Core\Exceptions\NotFoundException;
use Core\Panel\Infrastructure\PanelStandardController;

class TokenStandardController extends PanelStandardController
{

    function index()
    {
        $this->will('user', 'showToken');
        $this->addView('User', 'TokenList');
        $this->pushBreadcrumb(['title' => 'Token', 'url' => '/Token']);

    }

    /**
     * @param int $id
     * @throws NotFoundException
     */
    function view(int $id)
    {
        $this->will('user', 'showToken');
        $Token = new \User\Token();
        $data = $Token->getById($id);
        if ($data == null)
            throw new NotFoundException();
        $this->addView('User', 'TokenView', ['data' => $data]);
        $this->pushBreadcrumb(['title' => 'Token', 'url' => '/Token']);
        $this->pushBreadcrumb(['title' => 'Podgląd', 'url' => '/Token/view/'.$id]);
    }

    /**
     * @OfflineConstant
     */
    function add()
    {
        $this->will('user', 'addToken');
        $this->addView('User', 'TokenEdit', ['type' => 'add']);
        $this->pushBreadcrumb(['title' => 'Token', 'url' => '/Token']);
        $this->pushBreadcrumb(['title' => 'Dodaj', 'url' => '/Token/add']);
    }

    function add_data()
    {
        $this->will('user', 'addToken');
        $Token = new \User\Token();
        return ['selects' => $Token->getSelects()];
    }
}
