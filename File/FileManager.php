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
        $mimeOutput = $mime;
        if (!empty($_GET['type'])) {
            if ($_GET['type'] == 'png') {
                $mimeOutput = 'image/png';
            } else if ($_GET['type'] == 'jpg' || $_GET['type'] == 'jpeg') {
                $mimeOutput = 'image/jpeg';
            } else if ($_GET['type'] == 'webp') {
                $mimeOutput = 'image/webp';
            }
        }
        $extension = $this->mimeToExtension($mimeOutput);
        $tmpPath = __DIR__.'/../../Tmp/img_'.md5(($_GET['width'] ?? '').'_'.($_GET['height'] ?? '').'_'.($mimeOutput).'_'.$filepath).'.'.$extension;
        if (!is_file($tmpPath)) {
            if (!is_dir(__DIR__.'/../../Tmp/'))
                mkdir(__DIR__.'/../../Tmp/', 0777);
            $this->reformatImageDirect($filepath, $mime, $mimeOutput, $tmpPath);
        }
        header('content-type: '.$mimeOutput);
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
        } else if ($mime == 'image/webp') {
            return 'webp';
        } else {
            return 'bin';
        }
    }

    protected function reformatImageDirect($filepath, $mimeInput, $mimeOutput, $tmpPath)
    {
        if ($mimeInput == 'image/jpeg') {
            $image = imagecreatefromjpeg($filepath);
        } else if ($mimeInput == 'image/png') {
            $image = imagecreatefrompng($filepath);
        }


        if (!empty($_GET['width']) && !empty($_GET['height'])) {
            $image = $this->resizeImage($image, $_GET['width'], $_GET['height']);
        } else if (!empty($_GET['width'])) {
            $image = $this->resizeImage($image, $_GET['width'], round($_GET['width'] * imagesy($image) / imagesx($image)));
        } else if (!empty($_GET['height'])) {
            $image = $this->resizeImage($image, round($_GET['height'] * imagesx($image) / imagesy($image)), $_GET['height']);
        }

        if ($mimeOutput == 'image/jpeg') {
            imagejpeg($image, $tmpPath);
        } else if ($mimeOutput == 'image/png') {
            imagepng($image, $tmpPath);
        } else if ($mimeOutput == 'image/webp') {
            imagewebp($image, $tmpPath);
        }
    }

    function resizeImage($image, $dst_width, $dst_height)
    {
        $src_width = imagesx($image);
        $src_height = imagesy($image);
        $dst_image = imagecreatetruecolor($dst_width, $dst_height);
        $black = imagecolorallocate($dst_image, 0, 0, 0);
        imagecolortransparent($dst_image, $black);
        imagealphablending( $dst_image, false );
        imagesavealpha( $dst_image, true );
        imagecopyresampled($dst_image, $image, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        return $dst_image;
    }
}
