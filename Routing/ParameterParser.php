<?php


namespace Core\Routing;


use Core\ComponentManager\ComponentManager;
use Core\ComponentManager\ParamTypes\Content;
use Core\File\UploadedFile;
use Core\InSiteEdit\InSiteMapping;
use MKrawczyk\FunQuery\FunQuery;

class ParameterParser
{
    public $query;
    public $inSiteEdit;
    public $subComponents = [];

    public function __construct($query = [], $inSiteEdit = false)
    {
        $this->query = $query;
        $this->inSiteEdit = $inSiteEdit;
    }

    public function findParameters($definedParameters, $node)
    {
        if (empty($node->parameters))
            $paramsInfo = (object)[];
        else
            $paramsInfo = is_string($node->parameters) ? json_decode($node->parameters, false) : $node->parameters;

        return $this->parseParamStruct($definedParameters, $paramsInfo, []);
    }

    public function parseParamStruct($definedParameters, $params, $path = [])
    {
        $ret = new \stdClass();
        foreach ($definedParameters as $name => $def) {
            $def = (object)$def;
            $param = $params->{$name} ?? null;
            if ($param || $def->type == 'struct')
                $ret->{$name} = $this->parseParam((object)$def, $param, $name, [...$path, $name]);
            else
                $ret->{$name} = $def->default ?? $this->defaultByType($def->type);
        }
        return $ret;
    }

    private function parseParam($def, $param, $name, $path = [])
    {
        if ($def->type == 'array') {
            return $this->parseParamArray($def, $param);
        } else if ($def->type == 'tree') {
            return $this->parseParamTree($def, $param);
        } else if ($def->type == 'struct') {
            return $this->parseParamStruct($def->items, $param?->value ?? (new \stdClass()));
        } else {
            $const = $this->parseParamValue($param->value, $def->type, null, $path);
            if ($param->source == 'query')
                return $this->parseParamValue($this->query[$name] ?? $const, $def->type, $path);
            else if ($param->source == 'const')
                return $const;
        }
    }

    private function parseParamArray($def, $param)
    {
        return FunQuery::create($param->value ?? [])->map(fn($x) => $this->parseParamValue($x->value ?? null, $x->type, $def->item))->toArray();
    }

    private function parseParamValue($value, $type, $def = null, $path = [])
    {
        switch ($type) {
            case "struct":
                return empty($value) ? null : $this->parseParamStruct(((object)$def)->items, $value);
            case "int":
                return empty($value) ? null : (int)$value;
            case "boolean":
                return empty($value) ? false : (bool)$value;
            case "string":
                if ($this->inSiteEdit)
                    return InSiteMapping::addMapping($path, (string)$value);
                return (string)$value;
            case "component":
                $className = ComponentManager::findControllerClass($value->module, $value->component);
                $params = $this->parseParamStruct($className::DefinedParameters(), $value->params->value);
                $controller = new $className($params);
                $this->subComponents[] = $controller;
                return $controller;
            case "file":
                return FunQuery::create($value ?? [])->map(fn($x) => new UploadedFile($x))->toArray();
            case "image":
                return empty($value) ? null : UploadedFile::Create($value);
            case "imagesArray":
                return FunQuery::create($value ?? [])->map(fn($x) => UploadedFile::Create($x))->toArray();
            case "content":
                return empty($value) ? null : Content::Create($value);
            case "url":
                return empty($value) ? null : $this->parseParamUrl($value);
            default:
                throw new \Exception("not implemented type $type");
        }
    }

    private function parseParamTree($def, $param)
    {
        if ($param == null)
            return null;
        return FunQuery::create($param->value ?? [])->map(function ($x) use ($def) {
            $obj = $this->parseParamValue($x->value ?? null, $x->type);
            $obj->children = $this->parseParamTree($def, $x->children ?? null);
            return $obj;
        })->toArray();
    }

    private function parseParamUrl($value)
    {
        if (!empty($value))
            return $value;
        else
            throw new \Exception('Not implemented type of url');
    }

    private function defaultByType($type)
    {
        switch ($type) {
            case "struct":
                return new \stdClass();
            case "int":
                return 0;
            case "string":
                return '';
            case "boolean":
                return false;
            case "array":
                return [];
            case "image":
                return null;
            case "file":
                return null;
            case "content":
                return null;
            case "url":
                return '';
            case "component":
                return null;
            case "tree":
                return null;
            default:
                throw new \Exception("not implemented type");
        }
    }
}
