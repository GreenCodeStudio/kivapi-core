<?php


namespace Core\Panel\File\Ajax;


use Core\File\File;

class FileAjaxController extends \Core\Panel\Infrastructure\PanelAjaxController
{
    public function upload($file, $other)
    {
        return (new File())->upload($file);
    }

}