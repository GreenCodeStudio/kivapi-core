<?php
namespace Core\Panel\Page\Ajax;

use Core\Panel\Infrastructure\PanelAjaxController;

class PageAjaxController extends PanelAjaxController
{
    public function getTable($options)
    {
        $this->will('Page', 'show');
        $ComponentComposition = new \Core\ComponentManager\Page();
        return $ComponentComposition->getDataTable($options);
    }

    public function update($data)
    {
        $this->will('Page', 'edit');
        $ComponentComposition = new \Core\ComponentManager\Page();
        $ComponentComposition->update($data->id, $data);
    }

    public function insert($data)
    {
        $this->will('Page', 'add');
        $ComponentComposition = new \Core\ComponentManager\Page();
        $id = $ComponentComposition->insert($data);
        return $id;
    }
}