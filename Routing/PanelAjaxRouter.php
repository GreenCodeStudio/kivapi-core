<?php


namespace Core\Routing;


use Core\Exceptions\NotFoundException;

class PanelAjaxRouter extends AjaxRouter
{
    var $controllerType = 'Ajax';


    protected function parseUrl()
    {
        $exploded = explode('/', explode('?', $this->url)[0]);
        if (count($exploded) >= 6 && $exploded[3] == 'Package') {
            if (count($exploded) >= 7) {
                $vendorName = $exploded[4];
                $packageName = $exploded[5];
                $controllerName = $exploded[6];
                $methodName = empty($exploded[7]) ? 'index' : $exploded[7];
                $this->args = array_slice($exploded, 8);
            } else {
                $controllerName = 'PackageInfo';
                $methodName = 'details';
                $this->args = [$exploded[4], $exploded[5]];
            }
        } else {
            $controllerName = empty($exploded[3]) ? 'Start' : $exploded[3];
            $methodName = empty($exploded[4]) ? 'index' : $exploded[4];
            $this->args = array_slice($exploded, 5);
        }
        if (isset($vendorName)) {
            $this->vendorName = preg_replace('/[^a-zA-Z0-9_]/', '', $vendorName);
            $this->packageName = preg_replace('/[^a-zA-Z0-9_]/', '', $packageName);
        }
        $this->controllerName = preg_replace('/[^a-zA-Z0-9_]/', '', $controllerName);
        $this->methodName = preg_replace('/[^a-zA-Z0-9_]/', '', $methodName);
    }

    public function findControllerClass()
    {
        if ($this->vendorName) {

            $packagesGroupsPath = __DIR__.'/../../Packages';

            $filename = $packagesGroupsPath.'/'.$this->vendorName.'/'.$this->packageName.'/Panel/Ajax/'.$this->controllerName.'AjaxController.php';
            if (is_file($filename)) {
                include_once $filename;
                $className = "\\$this->vendorName\\$this->packageName\\Panel\\Ajax\\{$this->controllerName}AjaxController";
                return $className;
            }

        } else {
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
        }
        throw new NotFoundException();
    }

}
