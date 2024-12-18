<?php


namespace Core\Panel\File\Ajax;


use Core\File\File;
use Core\Panel\File\FileUploadException;

class FileAjaxController extends \Core\Panel\Infrastructure\PanelAjaxController
{
    public function upload($file, $other)
    {
        if($file['error']===1){
            throw new FileUploadException('File is too large, max size is '.ini_get('upload_max_filesize'));
        }else if($file['error']){
            throw new FileUploadException('Unknown error while uploading file (error code: '.$file['error'].')');
        }
        return (new File())->upload($file);
    }

}
