<?php
include __DIR__.'/Build/Builder.php';
//(new \Core\Build\Builder())->buildOnce();
(new \Core\Build\Builder())->buildWatch();