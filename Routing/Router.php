<?php


namespace Core\Routing;


use Core\File\AssetManager;
use Core\Exceptions\NotFoundException;
use Core\File\UploadedFileManager;
use Core\Log;
use Core\Panel\Authorization\Authorization;
use Core\Panel\Authorization\Exceptions\NoPermissionException;
use Core\Panel\Authorization\Exceptions\UnauthorizedException;
use Core\SpecialPages\ISpecialPage;
use Core\SpecialPages\Robots;
use Core\SpecialPages\Sitemap;
use mindplay\annotations\AnnotationCache;
use mindplay\annotations\Annotations;
use ReflectionMethod;

class Router
{
    public static function routeHttp($url)
    {
        if (strtolower(substr($url, 0, 8)) == '/assets/') {
            (new AssetManager())->get(substr($url, 8));
            exit;
        } else if (strtolower(substr($url, 0, 6)) == '/file/') {
            (new UploadedFileManager())->get(substr($url, 6));
            exit;
        } else if ($specialPage = self::getSpecialPage($url)) {
            $specialPage->generate();
            exit;
        }
        $router = self::getHttpRouter($url);
        try {
            $router->url = $url;
            $router->findController();
            $router->invoke();
        } catch (\Throwable $ex) {
            $router->sendBackException($ex);
            error_log($ex);
            return;
        }
        $router->sendBackSuccess();
    }

    protected static function getHttpRouter(string $url): Router
    {
        if ($url == "/panel" || substr($url, 0, 7) === '/panel/') {
            if (substr($url, 0, 12) === '/panel/ajax/') {
                return new PanelAjaxRouter();
            } else {
                if (isset($_SERVER['HTTP_X_JSON'])) {
                    return new StandardJsonPanelRouter();
                } else {
                    return new StandardPanelRouter();
                }
            }
        } else {
            if (substr($url, 0, 6) === '/ajax/') {
                return new AjaxRouter();
            } else if (isset($_SERVER['HTTP_X_JSON'])) {
                return new ComponentJsonRouter();
            } else {
                return new ComponentRouter();
            }
        }
    }

    protected function invoke()
    {
        ob_start();
        $this->runMethod();
        $debug = ob_get_contents();
        ob_get_clean();
        if (!empty($debug))
            dump($debug);
    }

    protected function runMethod()
    {
        $reflectionMethod = new ReflectionMethod($this->controllerClassName, $this->controller->initInfo->methodName);
        $this->returned = $reflectionMethod->invokeArgs($this->controller, $this->controller->initInfo->methodArguments);

        if (method_exists($this->controller, $this->controller->initInfo->methodName . '_data')) {
            $reflectionMethodData = new ReflectionMethod($this->controllerClassName, $this->controller->initInfo->methodName . '_data');
            $this->controller->initInfo->data = $reflectionMethodData->invokeArgs($this->controller, $this->controller->initInfo->methodArguments);
        }
    }

    protected function sendBackException(\Throwable $ex)
    {
        http_response_code($this->getHttpCode($ex));
        $this->logExceptionIfNeeded($ex);
        dump($ex);
    }

    protected function getHttpCode(\Throwable $ex)
    {
        if ($ex instanceof NotFoundException)
            return 404;
        else if ($ex instanceof NoPermissionException)
            return 403;
        else if ($ex instanceof UnauthorizedException)
            return 401;
        else
            return 500;
    }

    protected function logExceptionIfNeeded(\Throwable $ex)
    {
        if (!($ex instanceof NotFoundException) && !($ex instanceof NoPermissionException) && !($ex instanceof UnauthorizedException)) {
            error_log($ex);
            //Log::Exception($ex);
        }
    }

    public static function routeConsole($controllerName, $methodName, $args)
    {
        $router = new ConsoleRouter();
        try {
            $router->controllerName = $controllerName;
            $router->methodName = $methodName;
            $router->args = $args;
            $router->findController();
            $router->invoke();
        } catch (\Throwable $ex) {
            $router->sendBackException($ex);
            return;
        }
        $router->sendBackSuccess();
    }

    public static function listControllers(string $type)
    {
        $ret = [];
        $modules = scandir(__DIR__ . '/../../');
        foreach ($modules as $module) {
            if ($module == '.' || $module == '..') {
                continue;
            }
            if (is_dir(__DIR__ . '/../../' . $module . '/' . $type)) {
                $controllers = scandir(__DIR__ . '/../../' . $module . '/' . $type);
                foreach ($controllers as $controllerFile) {
                    $info = self::getControllerInfo($type, $module, $controllerFile);
                    if ($info != null) {
                        $ret[$info->name] = $info;
                    }
                }

            }
        }
        return $ret;
    }

    static function getControllerInfo($type, $module, $controllerFile): ?object
    {
        self::initAnnotationsCache();
        if (preg_match('/^(.*)\.php$/', $controllerFile, $matches)) {
            $name = $matches[1];
            $controllerInfo = new \StdClass();
            $controllerInfo->module = $module;
            $controllerInfo->name = $name;
            $controllerInfo->methods = [];
            try {
                $classPath = "\\$module\\$type\\$name";
                $controllerInfo->classPath = $classPath;
                $classReflect = new \ReflectionClass($classPath);
                $methods = $classReflect->getMethods();
                foreach ($methods as $methodReflect) {
                    if (!$methodReflect->isPublic()) continue;
                    if ('\\' . $methodReflect->class != $classPath) continue;
                    $methodInfo = new \StdClass();
                    $annotations = Annotations::ofMethod($classPath, $methodReflect->getName());
                    $methodInfo->name = $methodReflect->getName();
                    $methodInfo->parameters = $methodReflect->getParameters();
                    $methodInfo->annotations = $annotations;
                    $controllerInfo->methods[$methodReflect->getName()] = $methodInfo;
                }
            } catch (\Throwable $ex) {
                return null;
            }
            return $controllerInfo;
        }
        return null;
    }

    protected static function initAnnotationsCache(): void
    {
        if (empty(Annotations::$config['cache']))
            Annotations::$config['cache'] = new AnnotationCache(__DIR__ . '/../../../cache');
    }

    protected function parseUrl()
    {
        $exploded = explode('/', explode('?', $this->url)[0]);
        $controllerName = empty($exploded[1]) ? 'Start' : $exploded[1];
        $methodName = empty($exploded[2]) ? 'index' : $exploded[2];
        $this->args = array_slice($exploded, 3);
        $this->controllerName = preg_replace('/[^a-zA-Z0-9_]/', '', $controllerName);
        $this->methodName = preg_replace('/[^a-zA-Z0-9_]/', '', $methodName);
    }

    protected function exceptionToArray(\Throwable $exception)
    {
        $ret = ['type' => get_class($exception), 'message' => $exception->getMessage(), 'code' => $exception->getCode()];

        $debugEnabled = isset($_ENV['debug']) && $_ENV['debug'] === 'true';

        if ($debugEnabled) {
            $stack = [['file' => $exception->getFile(), 'line' => $exception->getLine()]];
            $stack = array_merge($stack, $exception->getTrace());
            $ret['stack'] = $stack;
        }

        return $ret;
    }


    protected function prepareController()
    {
        $cachedCode = isset($_ENV['cached_code']) ? $_ENV['cached_code'] : false;

        if ($cachedCode) {
            // $controllerClassName = static::findControllerCached($controllerName, $type);
        } else {
            $this->controllerClassName = $this->findControllerClass();
        }

        $this->controller = new $this->controllerClassName();

        if (!$this->controller->hasPermission($this->methodName)) {
            if (Authorization::isLogged()) {
                throw new NoPermissionException();
            } else {
                throw new UnauthorizedException();
            }
        }

        $this->controller->preAction();
    }


    protected function prepareMethod()
    {
        if (!method_exists($this->controller, $this->methodName)) {
            throw new NotFoundException();
        }

        $this->controller->initInfo->controllerName = $this->controllerName;
        $this->controller->initInfo->methodName = $this->methodName;
        $this->controller->initInfo->methodArguments = $this->args;
    }

    private static function getSpecialPage($url): ?ISpecialPage
    {
        if ($url == '/robots.txt') {
            return new Robots();
        } else if ($url == '/sitemap.xml') {
            return new Sitemap();
        } else {
            return null;
        }
    }
}