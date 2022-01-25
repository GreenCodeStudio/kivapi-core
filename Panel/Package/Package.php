<?php

namespace Core\Panel\Package;

use MKrawczyk\FunQuery\FunQuery;
use stdClass;

class Package
{
    public function getDataTable($options)
    {
        $all = FunQuery::create($this->listAllPackages());
        $total = $all->count();
        $rows = $all->slice($options->start, $options->limit);
        return ['rows' => $rows, 'total' => $total];
    }

    public function listAllPackages()
    {
        $dir = __DIR__ . '/../../../Packages';
        foreach (scandir($dir) as $vendor) {
            if ($vendor == '.' || $vendor == '..') continue;
            $vendorDir = $dir . '/' . $vendor;
            foreach (scandir($vendorDir) as $package) {
                if ($package == '.' || $package == '..') continue;
                yield $this->getPackageDetails($vendor, $package);
            }
        }
    }

    public function getPackageDetails(string $vendor, string $name)
    {
        $dir = __DIR__ . '/../../../Packages/' . $vendor . '/' . $name;
        if (file_exists($dir . '/package.xml')) {
            $ret = $this->readXML($dir . '/package.xml');
        } else {
            $ret = new StdClass();
        }
        $ret->name = $name;
        $ret->vendor = $vendor;
        $ret->fullName = $vendor . '/' . $name;
        return $ret;
    }

    private function readXML($path)
    {
        $ret = new StdClass();
        $xml = simplexml_load_file($path);
        if (isset($xml->git)) {
            $ret->git = $xml->git->__toString();
        }
        if (isset($xml->description)) {
            $ret->description = $xml->description->__toString();
        }
        if (isset($xml->version)) {
            $ret->version = $xml->version->__toString();
        }
        if (isset($xml->vendor)) {
            $ret->vendor = $xml->vendor->__toString();
        }
        if (isset($xml->name)) {
            $ret->name = $xml->name->__toString();
        }
        return $ret;
    }

    public function prepareInstallation(string $url)
    {
        $tmpID = uniqid();
        $tmpDir = sys_get_temp_dir() . '/' . $tmpID;
        mkdir($tmpDir);
        system("git clone $url $tmpDir");
        if (!file_exists($tmpDir . '/package.xml'))
            throw new \Exception("No package.xml");
        $xml = $this->readXML($tmpDir . '/package.xml');
        return ['details' => $xml, 'url' => $url, 'tmpId' => $tmpID];
    }

    public function install(string $tmpID, string $url)
    {
        $tmpDir = sys_get_temp_dir() . '/' . $tmpID;
        $xml = $this->readXML($tmpDir . '/package.xml');
        chdir(__DIR__ . '/../../..');
        $dir = "Packages/$xml->vendor/$xml->name";
        if (!is_dir("Packages/$xml->vendor")) {
            mkdir("Packages/$xml->vendor");
        }
        if (is_dir(__DIR__ . '/../../../.git'))
            system("git submodule add $url $dir");
        else
            copy($tmpDir, $dir);
    }
}