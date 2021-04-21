<?php


namespace Core\File;


class FileManager
{
    public function output($filepath, $mime)
    {
        if ($this->isImageReformat() && ($mime == 'image/jpeg' || $mime == 'image/png')) {
            $this->reformatImage($filepath, $mime);
        } else {
            //header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('content-type: '.$mime);
            $file = fopen($filepath, 'r');
            while ($data = fread($file, 1024)) {
                echo $data;
            }
        }
        exit;
    }

    protected function isImageReformat(): bool
    {
        return !(empty($_GET['width']) && empty($_GET['height']) && empty($_GET['type']));
    }

    protected function reformatImage($filepath, $mime)
    {
        if ($mime == 'image/jpeg') {
            $image = imagecreatefromjpeg($filepath);
        } else if ($mime == 'image/png') {
            $image = imagecreatefrompng($filepath);
        }
        if (!empty($_GET['width']) && !empty($_GET['height'])) {
            $image = imagescale($image, $_GET['width'], $_GET['height']);
        } else if (!empty($_GET['width'])) {
            $image = imagescale($image, $_GET['width'], $_GET['width'] * imagesy($image) / imagesx($image));
        } else if (!empty($_GET['height'])) {
            $image = imagescale($image, $_GET['height'] * imagesx($image) / imagesy($image), $_GET['height']);
        }

        if (!empty($_GET['type'])) {
            if ($_GET['type'] == 'png') {
                $mime = 'image/png';
            } else if ($_GET['type'] == 'jpg' || $_GET['type'] == 'jpeg') {
                $mime = 'image/jpeg';
            }
        }

        if ($mime == 'image/jpeg') {
            header('content-type: image/jpeg');
            imagejpeg($image);
        } else if ($mime == 'image/png') {
            header('content-type: image/png');
            imagepng($image);
        }
        exit;
    }
}