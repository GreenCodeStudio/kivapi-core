<?php


namespace Core\File;


class UploadedImage extends UploadedFile
{
    public function __construct($data)
    {
        parent::__construct($data);
        $this->imageWidth = $data->image_width;
        $this->imageHeight = $data->image_height;
    }

    public function getSrcSet(float $width, float $height)
    {
        $maxScale = max($this->imageWidth / $width, $this->imageHeight / $height);
        $ret = [];
        for ($scale = 0.5; ; $scale *= 2) {
            if ($scale > $maxScale) $scale = $maxScale;
            $ret[] = $this->getSizedUrl($width * $scale, $height * $scale).' '.$scale.'x';
            if ($scale == $maxScale) break;
        }
        return implode(', ', $ret);
    }

    public function getSizedUrl(int $width, int $height)
    {
        return $this->getUrl()."?width=$width&height=$height";
    }
}