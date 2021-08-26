<?php

namespace Core\Panel\TrackingCode\Ajax;

use Core\Panel\Infrastructure\PanelAjaxController;
use Core\Panel\TrackingCode\TrackingCode;

class TrackingCodeAjaxController extends PanelAjaxController
{
    public function getTable($options)
    {
        $this->will('trackingCode', 'show');
        return (new TrackingCode())->getDataTable($options);
    }

    public function update($data)
    {
        $this->will('trackingCode', 'edit');
        (new TrackingCode())->update($data->id, $data);
    }

    public function insert($data)
    {
        $this->will('trackingCode', 'add');
        (new TrackingCode())->insert($data);
    }
}