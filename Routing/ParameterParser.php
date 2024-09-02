<?php


namespace Core\Routing;


use Core\ComponentManager\ComponentManager;
use Core\ComponentManager\ParamTypes\Content;
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
            $const=$this->parseParamValue($param->value, $param->type);
            if ($param->source == 'query')
                return $this->parseParamValue($this->query[$name] ?? $const, $param->type);
            else if ($param->source == 'const')
                return $const;
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
                return empty($value) ? null : UploadedFile::Create($value);
            case "imagesArray":
                return FunQuery::create($value ?? [])->map(fn($x) => UploadedFile::Create($x))->toArray();
            case "content":
                return empty($value) ? null : Content::Create($value);
            case "url":
                return empty($value) ? null : $this->parseParamUrl($value);
            default:
                throw new \Exception("not implemented type");
        }
    }

    private function parseParamTree($param)
    {
        if($param==null)
            return null;
        return FunQuery::create($param->value ?? [])->map(function ($x) {
            $obj = $this->parseParamValue($x->value ?? null, $x->type);
            $obj->children = $this->parseParamTree($x->children??null);
            return $obj;
        })->toArray();
    }

    private function parseParamUrl($value)
    {
        if(!empty($value))
            return $value;
        else
            throw new \Exception('Not implemented type of url');
    }
}
