<?php

namespace Core\ComponentManager\ParamTypes;
use DOMDocument;

class Content
{
    /**
     * @var mixed
     */
    public $data;

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
            return str_replace("\n","<br />\n", htmlspecialchars($this->data->text));
        else if ($this->data->mime == 'text/html')
            return $this->data->html;
        else
            throw new \Exception("Unknown mime");
    }
    public function getXHtml()
    {
        $html= $this->getHtml();
        $dom=new DOMDocument();
        $dom->loadHTML('<!Doctype html><html><head><meta charset="utf8"></head><body>'.$html.'</body></html>');
        return substr($dom->saveXML($dom->documentElement->lastChild), 6, -7);
    }

}
