<?php


namespace Core\ComponentManager;


use Core\Database\DB;
use Core\Database\Repository;

class PageRepository extends Repository
{
    private $listById = null;

    public function __construct()
    {
        $this->archiveMode = static::ArchiveMode_OnlyExisting;
    }

    public function getAll()
    {
        if ($this->listById == null)
            $this->load();

        return $this->listById;
    }

    private function load()
    {
        $list = DB::get("SELECT pv.*, p.id FROM page_version pv JOIN page p on pv.id = p.current_version_id");
        $this->listById = [];
        foreach ($list as &$item) {
            $this->listById[$item->id] =& $item;
        }
        foreach ($list as &$item) {
            if ($item->parent_id != null)
                $item->parent =& $this->listById[$item->parent_id];
        }
    }

    public function getDataTable($options)
    {
        $start = (int)$options->start;
        $limit = (int)$options->limit;
        $sqlOrder = $this->getOrderSQL($options);
        $rows = DB::get("SELECT pv.*, p.id FROM page_version pv JOIN page p on pv.id = p.current_version_id  $sqlOrder LIMIT $start,$limit");
        $total = DB::get("SELECT count(*) as count FROM page_version")[0]->count;
        return ['rows' => $rows, 'total' => $total];
    }

    private function getOrderSQL($options)
    {
        if (empty($options->sort))
            return "";
        else {
            $mapping = [];
            if (empty($mapping[$options->sort->col]))
                throw new Exception();
            return ' ORDER BY '.DB::safeKey($mapping[$options->sort->col]).' '.($options->sort->desc ? 'DESC' : 'ASC').' ';
        }
    }

    public function insertVersion($data)
    {
        return DB::insert('page_version', $data);
    }

    public function getById(int $id)
    {
        $defaultTable = $this->defaultTable();
        return DB::get("SELECT pv.*, p.id FROM page p JOIN page_version pv on pv.id = p.current_version_id WHERE p.id = ?", [$id])[0] ?? null;
    }

    public function defaultTable(): string
    {
        return 'page';
    }

    public function getLayouts()
    {
        return DB::get("SELECT p.id, pv.title FROM page p JOIN page_version pv on pv.id = p.current_version_id WHERE pv.type='layout'");
    }

    public function reverseRoute($module, $component)
    {
        return DB::get("SELECT p.id, path, title FROM page p JOIN page_version pv on pv.id = p.current_version_id WHERE component = ? AND module = ? LIMIT 1", [$component, $module])[0] ?? null;
    }
}