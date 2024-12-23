<?php


namespace Core\Routing;

use Core\Exceptions\NotFoundException;
use Core\Panel\Authorization\Exceptions\UnauthorizedException;

class StandardPanelRouter extends Router
{
    var $controllerType = 'Controllers';
    /**
     * @var false|string
     */
    private $htmlResult;

    public function findControllerClass()
    {
        $modulesPath = __DIR__.'/../Panel';
        $modules = scandir($modulesPath);
        foreach ($modules as $module) {
            if ($module == '.' || $module == '..') {
                continue;
            }
            $filename = $modulesPath.'/'.$module.'/StandardControllers/'.$this->controllerName.'StandardController.php';
            if (is_file($filename)) {
                include_once $filename;
                $className = "\\Core\\Panel\\$module\\StandardControllers\\{$this->controllerName}StandardController";
                return $className;
            }
        }
        $packagesGroupsPath = __DIR__.'/../../Packages';
        if (is_dir($packagesGroupsPath)) {
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
                    $filename = $packagesGroupsPath.'/'.$group.'/'.$package.'/Panel/StandardControllers/'.$this->controllerName.'StandardController.php';
                    if (is_file($filename)) {
                        include_once $filename;
                        $className = "\\$group\\$package\\Panel\\StandardControllers\\{$this->controllerName}StandardController";
                        return $className;
                    }
                }
            }
        }
        throw new NotFoundException();
    }

    protected function findController()
    {
        $this->parseUrl();
        $this->prepareController();
        $this->prepareMethod();
    }

    protected function parseUrl()
    {
        $exploded = explode('/', explode('?', $this->url)[0]);
        if (count($exploded) >= 5 && $exploded[2] == 'Package') {
            if (count($exploded) >= 6) {
                $controllerName = $exploded[5];
                $methodName = empty($exploded[6]) ? 'index' : $exploded[6];
                $this->args = array_slice($exploded, 7);
            } else {
                $controllerName = 'PackageInfo';
                $methodName = 'details';
                $this->args = [$exploded[3], $exploded[4]];
            }
        } else {
            $controllerName = empty($exploded[2]) ? 'Start' : $exploded[2];
            $methodName = empty($exploded[3]) ? 'index' : $exploded[3];
            $this->args = array_slice($exploded, 4);
        }
        $this->controllerName = preg_replace('/[^a-zA-Z0-9_]/', '', $controllerName);
        $this->methodName = preg_replace('/[^a-zA-Z0-9_]/', '', $methodName);
    }

    protected function sendBackSuccess()
    {
        echo $this->htmlResult;
    }

    protected function sendBackException(\Throwable $ex)
    {
        $responseCode = $this->getHttpCode($ex);
        if ($responseCode == 404) {
            header('Location: /');
            $responseCode = 302;
        }
        http_response_code($responseCode);
        $this->logExceptionIfNeeded($ex);
        dump($ex);

        try {
            $this->prepareErrorController($ex, $responseCode);
        } catch (\Throwable $ex2) {
            dump($ex);
            $this->htmlResult = 'Error';
        }
        echo $this->htmlResult;
        dump_render_html();
    }

    protected function prepareErrorController($ex, $responseCode)
    {
        if ($ex instanceof UnauthorizedException) {
            $this->controllerName = 'Authorization';
            $this->methodName = 'index';
        } else {
            $this->controllerName = 'Error';
            $this->methodName = 'index';
        }
        $this->prepareController();
        $this->prepareMethod();
        $this->controller->initInfo->error = static::exceptionToArray($ex);
        $this->controller->initInfo->code = $responseCode;
        $this->controller->initInfo->methodArguments = [$responseCode];
        $this->invoke();
    }

    protected function invoke()
    {
        ob_start();
        $this->runMethod();
        $debug = ob_get_contents();
        ob_get_clean();
        if (!empty($debug))
            dump($debug);

        ob_start();
        $this->controller->postAction();
        $this->htmlResult = ob_get_contents();
        ob_get_clean();
    }
}
