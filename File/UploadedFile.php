<?php

namespace Core\File;

class UploadedFile
{
    public function __construct($data)
    {
        $this->id = $data->id;
        $this->mime = $data->mime;
        $this->size = $data->size;
        $this->extension = $data->extension;
        $this->name = $data->name;
    }

    public function getUrl()
    {
        return '/file/'.$this->id;
    }
}