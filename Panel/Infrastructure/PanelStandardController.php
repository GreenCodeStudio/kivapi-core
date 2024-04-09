<?php

namespace Core\Panel\Infrastructure;

use Core\Panel\Authorization\Authorization;
use Core\Panel\Infrastructure\Menu;

class PanelStandardController extends PanelController
{

    private $views = [];
    private $breadcrumb = [];

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
        $a = __DIR__;
        ob_start();
        if (\strpos($module, '/') > 0) {
            require __DIR__.'/../../../Packages/'.$module.'/Panel/Views/'.$name.'.php';
        } else {
            require __DIR__.'/../'.$module.'/Views/'.$name.'.php';
        }
        $this->views[$group][] = ob_get_contents();
        ob_end_clean();
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
}
