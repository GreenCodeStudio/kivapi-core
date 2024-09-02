<?php


namespace Core\Routing;


use Core\Exceptions\NotFoundException;

class PanelAjaxRouter extends AjaxRouter
{
    var $controllerType = 'Ajax';


    protected function parseUrl()
    {
        $exploded = explode('/', explode('?', $this->url)[0]);
        $controllerName = empty($exploded[3]) ? 'Start' : $exploded[3];
        $methodName = empty($exploded[4]) ? 'index' : $exploded[4];
        $this->controllerName = preg_replace('/[^a-zA-Z0-9_]/', '', $controllerName);
        $this->methodName = preg_replace('/[^a-zA-Z0-9_]/', '', $methodName);
    }

    public function findControllerClass()
    {
        $modulesPath = __DIR__.'/../Panel';
        $modules = scandir($modulesPath);
        foreach ($modules as $module) {
            if ($module == '.' || $module == '..') {
                continue;
            }
            $filename = $modulesPath.'/'.$module.'/Ajax/'.$this->controllerName.'AjaxController.php';
            if (is_file($filename)) {
                include_once $filename;
                $className = "\\Core\\Panel\\$module\\Ajax\\{$this->controllerName}AjaxController";
                return $className;
            }
        }
        $packagesGroupsPath = __DIR__.'/../../Packages';
        $packagesGroups = scandir($packagesGroupsPath);
        foreach ($packagesGroups as $group) {
            if ($group == '.' || $group == '..') {
                continue;
            }
            $packages = scandir($packagesGroupsPath.'/'.$group);
            foreach ($packages as $package) {
                if ($package == '.' || $package == '..') {
                    continue;
                }
                $filename = $packagesGroupsPath.'/'.$group.'/'.$package.'/Panel/Ajax/'.$this->controllerName.'AjaxController.php';
                if (is_file($filename)) {
                    include_once $filename;
                    $className = "\\$group\\$package\\Panel\\Ajax\\{$this->controllerName}AjaxController";
                    return $className;
                }
            }
        }
        throw new NotFoundException();
    }

}
