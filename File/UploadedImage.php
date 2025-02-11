<?php


namespace Core\File;


class UploadedImage extends UploadedFile
{

    public int $imageWidth;
    public int $imageHeight;

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
            $ret[] = $this->getSizedUrl((int)($width * $scale), (int)($height * $scale), $type).' '.$scale.'x';
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
    public function getSizedUrlContain(int $width, int $height, ?string $type = null)
    {
        $wantedRatio = $width / $height;
        $imageRatio = $this->imageWidth / $this->imageHeight;
        if ($wantedRatio > $imageRatio) {
            $newWidth = $height * $imageRatio;
            $newHeight = $height;
        } else {
            $newWidth = $width;
            $newHeight = $width / $imageRatio;
        }
        return $this->getSizedUrl((int)$newWidth, (int)$newHeight, $type);
    }
    public function getSrcSetContain(int $width, int $height, ?string $type = null)
    {
        $wantedRatio = $width / $height;
        $imageRatio = $this->imageWidth / $this->imageHeight;
        if ($wantedRatio > $imageRatio) {
            $newWidth = $height * $imageRatio;
            $newHeight = $height;
        } else {
            $newWidth = $width;
            $newHeight = $width / $imageRatio;
        }
        return $this->getSrcSet((int)$newWidth, (int)$newHeight, $type);
    }
    public function getSrcSetCover(int $width, int $height, ?string $type = null)
    {
        $wantedRatio = $width / $height;
        $imageRatio = $this->imageWidth / $this->imageHeight;
        if ($wantedRatio > $imageRatio) {
            $newWidth = $width;
            $newHeight = $width / $imageRatio;
        } else {
            $newWidth = $height * $imageRatio;
            $newHeight = $height;
        }
        return $this->getSrcSet((int)$newWidth, (int)$newHeight, $type);
    }
}
