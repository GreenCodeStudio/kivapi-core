<?php
namespace Core\InSiteEdit;
class InSiteMapping
{
    public static $mapping = [];

    public static function addMapping($path, $value)
    {
        $random = uniqid();
        static::$mapping[] = (object)['path' => $path, 'random' => $random, 'value' => $value];
        return $random;
    }
}

