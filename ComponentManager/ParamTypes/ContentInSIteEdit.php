<?php

namespace Core\ComponentManager\ParamTypes;
use DOMDocument;

class ContentInSiteEdit
{
    /**
     * @var mixed
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function Create($data): ContentInSIteEdit
    {
        return new ContentInSIteEdit($data);
    }

    public function getHtml()
    {
        return '<div data-in-site-edit-content="'.htmlspecialchars(json_encode($this->data), ENT_QUOTES, 'UTF-8').'"></div>';
    }

}
