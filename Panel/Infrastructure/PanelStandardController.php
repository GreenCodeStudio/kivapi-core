<?php

namespace Core\Panel\Infrastructure;

use Core\Panel\Authorization\Authorization;
use Core\Panel\Infrastructure\Menu;
use MKrawczyk\Mpts\Environment;
use MKrawczyk\Mpts\Parser\HTMLParser;
use MKrawczyk\Mpts\Parser\XMLParser;

class PanelStandardController extends PanelController
{

    private $views = [];
    private $breadcrumb = [];
    private $methodReturnData = null;

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumb[] = ['title' => t('Core.Panel.Common.Template.MainPage'), 'url' => '/'];
    }

    public function postAction()
    {
        $userData = Authorization::getUserData();
        $menu = new Menu();
        $menuData = $menu->readMenu();
        $this->addView('Common', 'aside', ['menu' => $menuData], 'aside');
        require __DIR__.'/../Common/Views/template.php';
    }

    public function getViews()
    {
        return $this->views;
    }

    public function getBreadcrumb()
    {
        return $this->breadcrumb;
    }

    public function getTitle()
    {
        $breadcrumb = array_map(function ($x) {
            return $x['title'];
        }, $this->breadcrumb);
        $breadcrumb[0] = "CMS";

        return implode(' - ', array_reverse($breadcrumb));
    }

    protected function addViewString(string $html, string $group = 'main')
    {
        $this->views[$group][] = $html;
    }

    protected function addView(string $module, string $name, $data = null, string $group = 'main')
    {
        if (\strpos($module, '/') > 0) {
            $filename = __DIR__.'/../../../Packages/'.$module.'/Panel/Views/'.$name.'.';
        } else {
            $filename = __DIR__.'/../'.$module.'/Views/'.$name.'.';
        }
        if (file_exists($filename.'mpts')) {
            $this->views[$group][] = $this->loadMPTS($filename.'mpts', $data);
        } else if (file_exists($filename.'php')) {
            ob_start();
            include $filename.'php';
            $this->views[$group][] = ob_get_contents();
            ob_end_clean();
        } else {
            throw new \Exception("View $name in module $module not found");
        }
    }

    private function loadMPTS($fileName, $data)
    {
        $template = HTMLParser::ParseFile($fileName);
        $env = new Environment();
        $env->variables = (array)$data;
        $env->variables['data'] ??= (array)$data;
        $env->variables['getView'] ??= fn(...$args) => $this->getView(...$args);
        $env->variables['dump'] = function ($x) {
            return print_r($x, true);
        };
        $env->variables['t'] = function ($x) {
            return t($x);
        };

        return $template->executeToStringXml($env);
    }

    protected function getView(string $module, string $name, $data = null)
    {
        if (\strpos($module, '/') > 0) {
            $filename = __DIR__.'/../../../Packages/'.$module.'/Panel/Views/'.$name.'.';
        } else {
            $filename = __DIR__.'/../'.$module.'/Views/'.$name.'.';
        }
        if (file_exists($filename.'mpts')) {
            return $this->loadMPTS($filename.'mpts', $data);
        } else if (file_exists($filename.'php')) {
            ob_start();
            include $filename.'php';
            $ret = ob_get_contents();
            ob_end_clean();
            return $ret;
        } else {
            throw new \Exception("View $name in module $module not found");
        }
    }

    protected function showViews(string $group)
    {
        if ($group == 'main') {
            global $debugArray;
            if (!empty($debugArray)) {
                echo '<div class="debugOutput">';
                dump_render_html();
                echo '</div>';
            }
        }
        if ($group == 'main')
            echo '<div class="page">';
        foreach ($this->views[$group] ?? [] as $html) {
            echo $html;
        }
        if ($group == 'main')
            echo '</div>';
    }

    protected function showBreadcrumb()
    {
        echo '<ul>';
        foreach ($this->breadcrumb as $crumb) {
            if (!empty($crumb['url']))
                echo '<li><a href="'.htmlspecialchars($crumb['url']).'">'.htmlspecialchars($crumb['title']).'</a></li>';
            else
                echo '<li><span>'.htmlspecialchars($crumb['title']).'</span></li>';
        }
        echo '</ul>';
    }

    protected function pushBreadcrumb($crumb)
    {
        $this->breadcrumb[] = $crumb;
    }

    protected function setData($data)
    {
        $this->initInfo->data = $data;
    }
}
