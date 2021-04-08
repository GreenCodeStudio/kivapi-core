<?php


namespace Core\Routing;


use Core\ComponentManager\ComponentManager;
use Core\File\UploadedFile;
use MKrawczyk\FunQuery\FunQuery;

class ParameterParser
{
    public function __construct($query = [])
    {
        $this->query = $query;
    }

    public function findParameters($node)
    {
        if (empty($node->parameters))
            return new \stdClass();
        $paramsInfo = is_string($node->parameters) ? json_decode($node->parameters, false) : $node->parameters;

        return $this->parseParamStruct($paramsInfo);
    }

    public function parseParamStruct($paramsInfo)
    {
        $ret = new \stdClass();
        foreach ($paramsInfo as $name => $param) {
            $ret->{$name} = $this->parseParam($param, $name);
        }
        return $ret;
    }

    private function parseParam($param, $name)
    {
        if ($param->type == 'array') {
            return $this->parseParamArray($param);
        } else if ($param->type == 'tree') {
            return $this->parseParamTree($param);
        } else if ($param->type == 'struct') {
            return $this->parseParamStruct($param->value);
        } else {
            if ($param->source == 'query')
                return $this->parseParamValue($this->query[$name] ?? null, $param->type);
            else if ($param->source == 'const')
                return $this->parseParamValue($param->value, $param->type);
        }
    }

    private function parseParamArray($param)
    {
        return FunQuery::create($param->value ?? [])->map(fn($x) => $this->parseParamValue($x->value ?? null, $x->type))->toArray();
    }

    private function parseParamValue($value, $type)
    {
        switch ($type) {
            case "struct":
                return empty($value) ? null : $this->parseParamStruct($value);
            case "int":
                return empty($value) ? null : (int)$value;
            case "string":
                return (string)$value;
            case "component":
                return ComponentManager::findController($value->module, $value->component, $this->parseParamStruct($value->params), []);
            case "file":
                return FunQuery::create($value ?? [])->map(fn($x) => new UploadedFile($x))->toArray();
            case "image":
                return empty($value) ? null : new UploadedFile($value);
            default:
                throw new \Exception("not implemented type");
        }
    }

    private function parseParamTree($param)
    {
        return FunQuery::create($param->value ?? [])->map(function ($x) {
            $obj = $this->parseParamValue($x->value ?? null, $x->type);
            $obj->children = $this->parseParamTree($x->children);
            return $obj;
        })->toArray();
    }
}