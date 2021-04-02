<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 04.07.2018
 * Time: 17:55
 */

namespace Core\Panel\Infrastructure;


class Menu
{
    function readMenu()
    {
        $root = [];
        foreach ($this->getPossibleFiles() as $filename) {
            if (is_file($filename)) {
                $xml = simplexml_load_string(file_get_contents($filename));
                foreach ($xml->children() as $element) {
                    $root[] = $this->getAsStdClass($element);
                }
            }
        }
        return $root;
    }

    function getPossibleFiles()
    {
        $modules = scandir(__DIR__.'/../');
        foreach ($modules as $module) {
            if ($module == '.' || $module == '..') {
                continue;
            }
            yield __DIR__.'/../'.$module.'/menu.xml';
        }
        $groups = scandir(__DIR__.'/../../../Packages/');
        foreach ($groups as $group) {
            if ($group == '.' || $group == '..') {
                continue;
            }
            $packages = scandir(__DIR__.'/../../../Packages/'.$group);
            foreach ($packages as $package) {
                if ($package == '.' || $package == '..') {
                    continue;
                }
                yield __DIR__.'/../../../Packages/'.$group.'/'.$package.'/menu.xml';
            }
        }
    }

    private function getAsStdClass(\SimpleXMLElement $element)
    {
        $ret = new \StdClass();
        foreach ($element->children() as $name => $value) {
            if ($name == 'menu') {
                $ret->menu = [];
                foreach ($value->children() as $childElement) {
                    $ret->menu[] = $this->getAsStdclass($childElement);
                }
            } else if ($name == 'permission') {
                $ret->permission = new \stdClass();
                foreach ($value as $childName => $childElement) {
                    $ret->permission->$childName = $childElement->__toString();
                }
            } else
                $ret->$name = $value->__toString();
        }
        return $ret;
    }
}