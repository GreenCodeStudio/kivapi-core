<?php


namespace Core\File;


class FileManager
{
    public function output($filepath, $mime)
    {
        header('cache-control: max-age=31536000, public');
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
        $tmpPath = __DIR__.'/../../Tmp/img_'.md5(($_GET['width'] ?? '').'_'.($_GET['width'] ?? '').'_'.($_GET['width'] ?? '').'_'.$filepath).'.'.$this->mimeToExtension($mime);
        if (!is_file($tmpPath)) {
            $this->reformatImageDirect($filepath, $mime, $tmpPath);
        }
        header('content-type: '.$mime);
        $file = fopen($tmpPath, 'r');
        while ($data = fread($file, 1024)) {
            echo $data;
        }
    }

    protected function mimeToExtension($mime)
    {
        if ($mime == 'image/jpeg') {
            return 'jpeg';
        } else if ($mime == 'image/png') {
            return 'png';
        } else {
            return 'bin';
        }
    }

    protected function reformatImageDirect($filepath, $mime, $tmpPath)
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
            imagejpeg($image, $tmpPath);
        } else if ($mime == 'image/png') {
            header('content-type: image/png');
            imagepng($image, $tmpPath);
        }
        exit;
    }
}