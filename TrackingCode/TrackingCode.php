<?php

namespace Core\TrackingCode;


use Core\TrackingCode\TrackingCodeRepository;


class TrackingCode
{
    private \Core\TrackingCode\TrackingCodeRepository $defaultDB;

    public function __construct()
    {
        $this->defaultDB = new TrackingCodeRepository();
    }

    public function getDataTable($options)
    {
        return $this->defaultDB->getDataTable($options);
    }

    public function update(int $id, $data)
    {
        $filtered = $this->filterData($data);
        $this->defaultDB->update($id, $filtered);
        //Sender::sendToUsers(["TrackingCode", "TrackingCode", "Update", $id]);
    }

    protected function filterData($data)
    {
        $ret = [];
        $ret['name'] = $data->name;
        $ret['is_active'] = !empty($data->is_active);
        $ret['header'] = $data->header;
        $ret['body_start'] = $data->body_start;
        $ret['body_end'] = $data->body_end;
        return $ret;
    }

    public function insert($data)
    {
        $filtered = $this->filterData($data);
        $id = $this->defaultDB->insert($filtered);
    }

    public function getById(int $id)
    {
        return $this->defaultDB->getById($id);
    }

    public function getActiveCodes()
    {
        return $this->defaultDB->getActiveCodes();
    }
}
