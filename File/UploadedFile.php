<?php

namespace Core\File;

class UploadedFile
{
    public $id;
    public $mime;
    public $size;
    public $extension;
    public $name;

    public function __construct($data)
    {
        $this->id = $data->id;
        $this->mime = $data->mime;
        $this->size = $data->size;
        $this->extension = $data->extension;
        $this->name = $data->name;
    }

    static public function Create($data): UploadedFile
    {
        if (!empty($data->image_width) && !empty($data->image_height)) {
            return new UploadedImage($data);
        } else {
            return new UploadedFile($data);
        }
    }

    public function getUrl()
    {
        return '/file/'.$this->id;
    }
}
