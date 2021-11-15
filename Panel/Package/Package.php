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
        $ret = new StdClass();
        $ret->name = $name;
        $ret->fullName = $vendor . '/' . $name;
        $dir = __DIR__ . '/../../../Packages/' . $vendor . '/' . $name;
        if (file_exists($dir . '/package.xml')) {
            $xml = simplexml_load_file($dir . '/package.xml');
            if (isset($xml->package->git)) {
                $ret->git = $xml->package->git;
            }
            if (isset($xml->package->description)) {
                $ret->git = $xml->package->description;
            }
        }
        return $ret;
    }
}