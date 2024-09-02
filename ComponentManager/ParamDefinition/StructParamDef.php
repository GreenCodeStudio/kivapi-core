<?php

namespace Core\ComponentManager\ParamDefinition;

class StructParamDef
{
    public string $type = 'struct';
    public array $items;

    public function __construct($items)
    {
        $this->items = $items;
    }
}
