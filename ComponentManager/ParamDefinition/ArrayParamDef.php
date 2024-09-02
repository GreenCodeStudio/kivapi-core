<?php

namespace Core\ComponentManager\ParamDefinition;

class ArrayParamDef
{
    public string $type;
    public object $item;

    public function __construct($item)
    {
        $this->type = 'array';
        $this->item = $item;
    }
}
