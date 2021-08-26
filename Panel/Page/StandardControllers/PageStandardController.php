<?php

namespace Core\Panel\Page\StandardControllers;

use Authorization\Permissions;
use Core\ComponentManager\ComponentManager;
use Core\ComponentManager\Page;
use Core\Exceptions\NotFoundException;
use Core\Panel\Infrastructure\PanelStandardController;

class PageStandardController extends PanelStandardController
{

    function index()
    {
        $this->will('Page', 'show');
        $this->addView('Page', 'PageList');
        $this->pushBreadcrumb(['title' => 'Page', 'url' => 'Page']);

    }

    function edit(int $id)
    {
        $this->will('Page', 'edit');
        $page = new Page();
        $layouts = $page->getLayouts();
        $this->addView('Page', 'PageEdit', ['type' => 'edit', 'layouts' => $layouts]);
        $this->pushBreadcrumb(['title' => 'Page', 'url' => 'Page']);
        $this->pushBreadcrumb(['title' => 'Edycja', 'url' => 'Page/edit/' . $id]);
    }

    function edit_data(int $id)
    {
        $this->will('Page', 'edit');
        $page = new Page();
        $data = $page->getById($id);
        if ($data == null)
            throw new NotFoundException();

        $availableComponents = ComponentManager::listComponents();
        return ['Page' => $data, 'availableComponents' => $availableComponents];
    }

    function show(int $id)
    {
        $this->will('Page', 'show');
        $ComponentComposition = new Page();
        $data = $ComponentComposition->getById($id);
        if ($data == null)
            throw new NotFoundException();
        $this->addView('Page', 'PageShow', ['Page' => $data]);
        $this->pushBreadcrumb(['title' => 'Page', 'url' => 'Page']);
        $this->pushBreadcrumb(['title' => 'Podgląd', 'url' => 'Page/show/' . $id]);
    }

    function add()
    {
        $this->will('Page', 'add');
        $page = new Page();
        $layouts = $page->getLayouts();
        $availableComponents = ComponentManager::listComponents();
        $this->addView('Page', 'PageEdit', ['type' => 'add', 'layouts' => $layouts, 'availableComponents' => $availableComponents]);
        $this->pushBreadcrumb(['title' => 'Page', 'url' => 'Page']);
        $this->pushBreadcrumb(['title' => 'Dodaj', 'url' => 'Page/add']);
    }
}
