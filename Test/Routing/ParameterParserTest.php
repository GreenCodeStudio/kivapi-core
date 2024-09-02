<?php
include_once __DIR__.'/../../autoloader.php';

use Core\Routing\ParameterParser;
use PHPUnit\Framework\TestCase;

class ParameterParserTest extends TestCase
{
    public function testEmpty()
    {
        $expected = new stdClass();

        $real = (new ParameterParser())->findParameters([], (object)['parameters' => null],);

        $this->assertEqualsCanonicalizing($expected, $real);
    }

    public function testNormal()
    {
        $node = (object)['parameters' => '{"id": {"type": "int", "value": "2", "source": "const"}}'];
        $definedParams = (object)[
            'id' => (object)['Title' => 'Id', 'type' => 'int', 'canFromQuery' => true]
        ];

        $expected = (object)['id' => 2];
        $real = (new ParameterParser())->findParameters($definedParams, $node,);


        $this->assertEqualsCanonicalizing($expected, $real);
    }

    public function testNewField()
    {
        $node = (object)['parameters' => '{"id": {"type": "int", "value": "2", "source": "const"}}'];
        $definedParams = (object)[
            'id' => (object)['Title' => 'Id', 'type' => 'int', 'canFromQuery' => true],
            'title' => (object)['Title' => 'Id', 'type' => 'string', 'default' => 'No name', 'canFromQuery' => false]
        ];

        $expected = (object)['id' => 2, 'title' => 'No name'];
        $real = (new ParameterParser())->findParameters($definedParams, $node,);


        $this->assertEqualsCanonicalizing($expected, $real);
    }

    public function testAllTypesEmpty()
    {

        $node = (object)['parameters' => null];
        $definedParams = (object)[
            'int' => (object)['type' => 'int'],
            'string' => (object)['type' => 'string'],
            'url' => (object)['type' => 'url'],
            'struct' => (object)['type' => 'struct', 'items' => [
                'mail' => (object)['type' => 'string'],
                'phone' => (object)['type' => 'string'],
            ]],
            'array' => (object)['type' => 'array'],
            'image' => (object)['type' => 'image'],
            'file' => (object)['type' => 'file'],
            'component' => (object)['type' => 'component'],
        ];

        $expected = (object)[
            'int' => 0,
            'string' => '',
            'url' => '',
            'struct' => (object)['mail' => '', 'phone' => ''],
            'array' => [],
            'image' => null,
            'file' => null,
            'component' => null,
        ];
        $real = (new ParameterParser())->findParameters($definedParams, $node,);
        $this->assertEquals($expected, $real);

    }

    public function testAllTypesDefault()

    {
        $node = (object)['parameters' => null];
        $definedParams = (object)[
            'int' => (object)['type' => 'int', 'default' => 5],
            'string' => (object)['type' => 'string', 'default' => 'default'],
            'url' => (object)['type' => 'url', 'default' => 'http://example.com'],
            'struct' => (object)['type' => 'struct', 'items' => [
                'mail' => (object)['type' => 'string', 'default' => 'a@a.com'],
                'phone' => (object)['type' => 'string', 'default' => '123'],
            ]],
            'array' => (object)['type' => 'array', 'default' => [1, 2, 3]],
        ];

        $expected = (object)[
            'int' => 5,
            'string' => 'default',
            'url' => 'http://example.com',
            'struct' => (object)['mail' => 'a@a.com', 'phone' => '123'],
            'array' => [1, 2, 3],
        ];
        $real = (new ParameterParser())->findParameters($definedParams, $node,);
        $this->assertEqualsCanonicalizing($expected, $real);

    }
}
