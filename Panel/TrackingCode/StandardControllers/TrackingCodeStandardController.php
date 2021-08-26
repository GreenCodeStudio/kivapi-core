<?php

namespace Core\Panel\TrackingCode\StandardControllers;


use Core\Exceptions\NotFoundException;
use Core\Panel\Authorization\Permissions;
use Core\Panel\Infrastructure\PanelStandardController;
use Core\Panel\TrackingCode\TrackingCode;

class TrackingCodeStandardController extends PanelStandardController
{

    function index()
    {
        $this->will('trackingCode', 'show');
        $this->addView('TrackingCode', 'list');
        $this->pushBreadcrumb(['title' => 'Tracking codes', 'url' => '/TrackingCode']);

    }

    function edit(int $id)
    {
        $this->will('trackingCode', 'edit');
        $this->addView('TrackingCode', 'edit', ['type' => 'edit']);
        $this->pushBreadcrumb(['title' => 'Tracking codes', 'url' => 'TrackingCode']);
        $this->pushBreadcrumb(['title' => 'Edycja', 'url' => 'TrackingCode/edit/'.$id]);
    }

    function edit_data(int $id)
    {
        $this->will('trackingCode', 'edit');
        $data = (new TrackingCode())->getById($id);
        if ($data == null)
            throw new NotFoundException();
        return ['trackingCode' => $data];
    }

    function add()
    {
        $this->will('trackingCode', 'add');
        $this->addView('TrackingCode', 'edit', ['type' => 'add']);
        $this->pushBreadcrumb(['title' => 'Tracking codes', 'url' => 'TrackingCode']);
        $this->pushBreadcrumb(['title' => 'Dodaj', 'url' => 'TrackingCode/add']);
    }

}
