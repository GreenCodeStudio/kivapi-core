<?php

namespace Core\TrackingCode;

use Core\Database\DB;
use Core\Database\Repository;
use stdClass;


class TrackingCodeRepository extends Repository
{

    public function __construct()
    {
        $this->archiveMode = static::ArchiveMode_OnlyExisting;
    }

    public function defaultTable(): string
    {
        return "tracking_code";
    }

    public function getDataTable($options)
    {
        $start = (int)$options->start;
        $limit = (int)$options->limit;
        $sqlOrder = $this->getOrderSQL($options);
        $rows = DB::get("SELECT * FROM tracking_code $sqlOrder LIMIT $start,$limit");
        $total = DB::get("SELECT count(*) as count FROM token")[0]->count;
        return ['rows' => $rows, 'total' => $total];
    }

    private function getOrderSQL($options)
    {
        if (empty($options->sort))
            return "";
        else {
            $mapping = ['name' => 'name', 'is_active' => 'is_active'];
            if (empty($mapping[$options->sort->col]))
                throw new \Exception();
            return ' ORDER BY '.DB::safeKey($mapping[$options->sort->col]).' '.($options->sort->desc ? 'DESC' : 'ASC').' ';
        }
    }

    public function getActiveCodes()
    {
        return DB::get("SELECT * FROM tracking_code WHERE is_active");
    }
}
