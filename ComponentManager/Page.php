<?php

namespace Core\ComponentManager;


use ReflectionMethod;

class Page
{
    public function __construct()
    {
        $this->defaultDB = new PageRepository();
    }

    public function getDataTable($options)
    {
        return $this->defaultDB->getDataTable($options);
    }

    public function getById(int $id)
    {
        $item = $this->defaultDB->getById($id);
        $className = ComponentManager::findControllerClass($item->module, $item->component);
        if (method_exists($className, 'DefinedParameters')) {
            $definedParametersMethod = new ReflectionMethod($className, 'DefinedParameters');
            $item->definedParameters = $definedParametersMethod->invoke(null);
        } else {
            $item->definedParameters = [];
        }
        return $item;
    }

    public function update(int $id, $data)
    {
        $old = $this->defaultDB->getById($id);
        $filtered = $this->filterData($data);
        $filtered['page_id'] = $id;
        $filtered['module'] = $old->module;
        $filtered['component'] = $old->component;
        $versionId = $this->defaultDB->insertVersion($filtered);
        $this->defaultDB->update($id, ['current_version_id' => $versionId]);
        //\Core\WebSocket\Sender::sendToUsers(["ComponentComposition", "ComponentComposition", "Update", $id]);
    }

    protected function filterData($data)
    {
        $ret = ['parameters' => $data->parameters, 'parent_id' => empty($data->parent_id) ? null:$data->parent_id, 'path' => $data->path, 'title' => $data->title, 'description' => $data->description];

        return $ret;
    }

    public function insert($data)
    {
        $filtered = $this->filterData($data);
        $component = json_decode($data->component);
        $filtered['module'] = $component[0];
        $filtered['component'] = $component[1];

        $className = ComponentManager::findControllerClass($component[0], $component[1]);
        $filtered['type'] = $className::type();
        $id = $this->defaultDB->insert([]);
        $filtered['page_id'] = $id;
        $versionId = $this->defaultDB->insertVersion($filtered);
        $this->defaultDB->update($id, ['current_version_id' => $versionId]);
        //\Core\WebSocket\Sender::sendToUsers(["ComponentComposition", "ComponentComposition", "Insert", $id]);
        return $id;
    }

}