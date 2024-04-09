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

    public function getSrcSet(float $width, float $height, ?string $type = null)
    {
        $maxScale = max($this->imageWidth / $width, $this->imageHeight / $height);
        $ret = [];
        for ($scale = 0.5; ; $scale *= 2) {
            if ($scale > $maxScale) $scale = $maxScale;
            $ret[] = $this->getSizedUrl($width * $scale, $height * $scale, $type).' '.$scale.'x';
            if ($scale == $maxScale) break;
        }
        return implode(', ', $ret);
    }

    private function getAllWidths()
    {
        for ($i = 1; $i <= 64; $i *= 2) {
            yield 256 * $i;
            yield 320 * $i;
            yield 480 * $i;
        }
    }

    public function getWidthPercentageSrcSet(float $widthPercentage = 100, ?string $type = null)
    {
        $ret = [];
        foreach ($this->getAllWidths() as $width) {
            $imgWidth = $width * $widthPercentage / 100;
            if ($imgWidth >= $this->imageWidth) {
                $imgWidth = $this->imageWidth;
                $width = $this->imageWidth / $widthPercentage * 100;
            }

            $imgHeight = $imgWidth / $this->imageWidth * $this->imageHeight;

            $ret[] = $this->getSizedUrl((int)$imgWidth, (int)$imgHeight, $type).' '.(int)$width.'w';

            if ($imgWidth >= $this->imageWidth)
                break;
        }
        return implode(', ', $ret);
    }

    public function getSizedUrl(int $width, int $height, ?string $type = null)
    {
        if ($type == null)
            return $this->getUrl()."?width=$width&height=$height";
        else
            return $this->getUrl()."?width=$width&height=$height&type=$type";
    }
}
