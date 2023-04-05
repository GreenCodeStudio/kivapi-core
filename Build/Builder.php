<?php

namespace Core\Build;

use Core\ComponentManager\ComponentManager;
use Core\Routing\ComponentRouter;

class Builder
{
    public function buildOnce()
    {
        $this->prepareWebpack();
        $this->prepareStyle();
        $this->prepareJs();
        $this->preparePanelStyle();
        $this->preparePanelJs();
        system("yarn webpack --mode=development");
    }

    public function buildWatch()
    {
        $this->prepareWebpack();
        $this->prepareStyle();
        $this->prepareJs();
        $this->preparePanelStyle();
        $this->preparePanelJs();
        system("yarn webpack --mode=development --watch");
    }

    public function prepareWebpack()
    {
        $path = __dir__ . '/../../Tmp/Build';
        $prebuildPath = __dir__ . '/../../Tmp/Prebuild';
        if (!is_dir($path))
            mkdir($path, 0777, true);
        if (!is_dir($prebuildPath))
            mkdir($prebuildPath, 0777, true);

        file_put_contents($prebuildPath . "/package.json", '{"dependencies":{"util.merge-packages":"^0.0.19"}}');
        copy(__DIR__ . '/build.js', $prebuildPath . '/build.js');
        chdir($prebuildPath);
        exec("yarn");
        exec('node build.js');
        chdir($path);
        exec("yarn");
        copy(__DIR__ . '/webpack.config.js', $path . '/webpack.config.js');
    }

    public function prepareJs()
    {
        $path = __dir__ . '/../../Tmp/Build/js.js';
        $content = [];
        foreach ($this->findAll('Js/index.js') as $file) {
            $content[] = "import \"../../$file\";";
        }
        $content[] = "import {ComponentManager} from \"../../Core/Js/ComponentManager\"";
        $availableComponents = ComponentManager::listComponents();
        foreach ($availableComponents as $component) {
            $file = "Components/$component[1]/JsController.js";
            if (is_file(__DIR__ . "/../../$file"))
                $content[] = "ComponentManager.register(" . json_encode($component[0]) . "," . json_encode($component[1]) . ", async ()=>(await import(\"../../$file\")).default);";
        }
        file_put_contents($path, implode("\r\n", $content));
    }

    public function prepareStyle()
    {
        $path = __dir__ . '/../../Tmp/Build/style.scss';
        $content = [];
        foreach ($this->findAll('Style/index.scss') as $file) {
            $content[] = "@import \"../../$file\";";
        }
        file_put_contents($path, implode("\r\n", $content));
    }

    public function preparePanelStyle()
    {
        $path = __dir__ . '/../../Tmp/Build/panelStyle.scss';
        $content = [];
        foreach ($this->findAll('Panel/Style/index.scss') as $file) {
            $content[] = "@import \"../../$file\";";
        }
        file_put_contents($path, implode("\r\n", $content));
    }

    public function preparePanelJs()
    {
        $path = __dir__ . '/../../Tmp/Build/panelJs.js';
        $content = [];
        foreach ($this->findAllPanel('Js/index.js') as $file) {
            $content[] = "import \"../../$file\";";
        }
        file_put_contents($path, implode("\r\n", $content));
    }

    public function findAll($subpath)
    {
        $ret = [];
        if (is_file(__DIR__ . '/../../' . $subpath))
            $ret[] = $subpath;
        if (is_file(__DIR__ . '/../../Core/' . $subpath))
            $ret[] = 'Core/' . $subpath;

        foreach (scandir(__DIR__ . "/../../Packages") as $group) {
            if ($group == '.' || $group == '..') continue;
            foreach (scandir(__DIR__ . "/../../Packages/$group") as $package) {
                if ($group == '.' || $group == '..') continue;
                if (is_file(__DIR__ . "/../../Packages/$group/$package"))
                    $ret[] = "Packages/$group/$package/";
            }
        }
        return $ret;
    }

    public function findAllPanel($subpath)
    {
        $ret = [];
        if (is_file(__DIR__ . '/../../' . $subpath))
            $ret[] = $subpath;
        if (is_file(__DIR__ . '/../../Core/Panel/' . $subpath))
            $ret[] = 'Core/Panel/' . $subpath;

        foreach (scandir(__DIR__ . "/../../Core/Panel") as $group) {
            if ($group == '.' || $group == '..') continue;
            if (is_file(__DIR__ . "/../../Core/Panel/$group/$subpath"))
                $ret[] = "Core/Panel/$group/$subpath";
        }
        if (is_dir (__DIR__ . "/../../Packages/") ) {
            foreach (scandir(__DIR__ . "/../../Packages/") as $group) {
                if ($group == '.' || $group == '..') continue;

                foreach (scandir(__DIR__ . "/../../Packages/" . $group) as $package) {
                    if ($package == '.' || $package == '..') continue;

                    if (is_file(__DIR__ . "/../../Packages/$group/$package/Panel/$subpath"))
                        $ret[] = "Packages/$group/$package/Panel/$subpath";
                }
            }
        }
        return $ret;
    }
}