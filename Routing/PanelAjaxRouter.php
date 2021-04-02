<?php


namespace Core\Routing;


use Core\Exceptions\NotFoundException;

class PanelAjaxRouter extends Router
{
    var $controllerType = 'Ajax';

    protected function findController()
    {
        $this->parseUrl();
        $this->parseParams();
        $this->prepareController();
        $this->prepareMethod();
    }

    protected function parseUrl()
    {
        $exploded = explode('/', explode('?', $this->url)[0]);
        $controllerName = empty($exploded[3]) ? 'Start' : $exploded[3];
        $methodName = empty($exploded[4]) ? 'index' : $exploded[4];
        $this->controllerName = preg_replace('/[^a-zA-Z0-9_]/', '', $controllerName);
        $this->methodName = preg_replace('/[^a-zA-Z0-9_]/', '', $methodName);
    }

    protected function parseParams()
    {
        $args = [];
        foreach ($_POST['args'] ?? [] as $key => $arg) {
            $args[$key] = json_decode($arg, false);
        }
        foreach (self::getFileArgs() as $key => $arg) {
            $args[$key] = $arg;
        }
        ksort($args);
        $this->args = $args;
    }

    protected static function getFileArgs()
    {
        if (empty($_FILES['args']))
            return [];
        $ret = [];
        $keys = array_keys($_FILES['args']['tmp_name']);
        foreach ($keys as $key) {
            $ret[$key] = [
                'type' => $_FILES['args']['type'][$key],
                'tmp_name' => $_FILES['args']['tmp_name'][$key],
                'size' => $_FILES['args']['size'][$key],
                'name' => $_FILES['args']['name'][$key],
                'error' => $_FILES['args']['error'][$key]
            ];
        }
        return $ret;
    }

    protected function sendBackSuccess()
    {
        header('Content-Type: application/json');
        global $debugArray;
        $debugEnabled = $_ENV['debug'] == 'true';
        echo json_encode(['data' => $this->returned, 'error' => null, 'debug' => $debugEnabled ? $debugArray : [], 'output' => $debugEnabled ? ($controller->debugOutput ?? '') : ''], JSON_PARTIAL_OUTPUT_ON_ERROR);
        $debugArray = [];
    }

    protected function sendBackException($ex)
    {
        header('Content-Type: application/json');
        $responseCode = $this->getHttpCode($ex);
        http_response_code($responseCode);
        $this->logExceptionIfNeeded($ex);
        dump($ex);

        global $debugArray;
        $debugEnabled = $_ENV['debug'] == 'true';
        echo json_encode(['error' => $this->exceptionToArray($ex), 'debug' => $debugEnabled ? $debugArray : [], 'output' => $debugEnabled ? ($controller->debugOutput ?? '') : ''], JSON_PARTIAL_OUTPUT_ON_ERROR);
        $debugArray = [];
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