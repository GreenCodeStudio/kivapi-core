<?php


namespace Core\Internationalization;


class TextsRepository
{
    static private ?I18nNode $root = null;

    static public function getRootNode(): I18nNode
    {
        self::loadIfNeeded();
        return self::$root;
    }

    static public function loadIfNeeded()
    {
        if (self::$root === null)
            self::load();
    }

    static private function load()
    {
        self::$root = new I18nNode();
        $coreNode = new I18nNode();
        $panelNode = new I18nNode();
        $coreNode->addChild("Panel", $panelNode);
        self::$root->addChild("Core", $coreNode);
        $modules = scandir(__DIR__.'/../Panel/');
        foreach ($modules as $module) {
            if ($module == '.' || $module == '..') {
                continue;
            }
            $filename = __DIR__.'/../Panel/'.$module.'/i18n.xml';
            if (is_file($filename)) {
                $panelNode->addChild($module, self::parseFile($filename));
            }
        }
        $packagesGroupsPath = __DIR__.'/../../Packages';
        $packagesGroups = scandir($packagesGroupsPath);
        foreach ($packagesGroups as $group) {
            if ($group == '.' || $group == '..') {
                continue;
            }
            $groupNode = new I18nNode();
            self::$root->addChild($group, $groupNode);
            $packages = scandir($packagesGroupsPath.'/'.$group);
            foreach ($packages as $package) {
                if ($package == '.' || $package == '..') {
                    continue;
                }
                $filename = $packagesGroupsPath.'/'.$group.'/'.$package.'/Panel/i18n.xml';
                if (is_file($filename)) {
                    $panelNode = new I18nNode();
                    $groupNode->addChild($package, $panelNode);
                    $panelNode->addChild('Panel', self::parseFile($filename));
                }
            }
        }
    }

    private static function parseFile(string $filename)
    {
        $xml = \simplexml_load_string(file_get_contents($filename));
        if ($xml === false) throw new \Exception("Bad i18n.xml file");
        return new I18nNode($xml);
    }
}