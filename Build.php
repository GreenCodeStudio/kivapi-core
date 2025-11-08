<?php
include_once __DIR__.'/../vendor/autoload.php';
include __DIR__.'/Build/Builder.php';
if ($watch ?? false) {
    (new \Core\Build\Builder())->buildWatch();
} else {
    (new \Core\Build\Builder())->buildOnce();
}
