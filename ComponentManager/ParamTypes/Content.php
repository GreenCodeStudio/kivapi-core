<?php

namespace Core\ComponentManager\ParamTypes;
class Content
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function Create($data): Content
    {
        return new Content($data);
    }

    public function getHtml()
    {
        if ($this->data->mime == 'text/plain')
            return htmlspecialchars($this->data->text);
        else if ($this->data->mime == 'text/html')
            return $this->data->html;
        else
            throw new \Exception("Unknown mime");
    }
}