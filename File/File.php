<?php


namespace Core\File;


class File
{

    public function upload($file)
    {
        $id = hash('sha1', uniqid(true));//sha to prevent guessing
        $fileSize = filesize($file['tmp_name']);
        move_uploaded_file($file['tmp_name'], $this->getDir().'/'.$id.'.bin');
        $lastDotPos = strrpos($file['name'], '.');
        if ($lastDotPos >= 0) {
            $name = substr($file['name'], 0, $lastDotPos);
            $extension = substr($file['name'], $lastDotPos + 1);
        } else {
            $name = $file['name'];
            $extension = null;
        }
        $data=['id' => $id, 'name' => $name, 'extension' => $extension, 'mime' => $file['type'], 'size' => $fileSize];
        (new FileRepository())->insert($data);
        return $data;
    }

    public function getDir()
    {
        $dir = __DIR__.'/../../UploadedFiles';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }
}