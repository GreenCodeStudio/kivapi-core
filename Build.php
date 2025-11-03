<?php
include __DIR__.'/Build/Builder.php';
if($watch??false){
    (new \Core\Build\Builder())->buildWatch();
}else{
    (new \Core\Build\Builder())->buildOnce();
}
