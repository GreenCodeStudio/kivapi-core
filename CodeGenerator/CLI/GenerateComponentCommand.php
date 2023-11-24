<?php

namespace Core\CodeGenerator\CLI;

class GenerateComponentCommand extends \Kivapi\KivapiCli\Commands\AbstractCommand
{
    public static function getParameters(): array
{
    return [
        'name' => (object)['type' => 'string', 'required' => true],
    ];
}

    public function execute()
    {
        $root=__DIR__.'/../../../';
        if(!is_dir($root.'Components')) mkdir($root.'Components');
        if(!is_dir($root.'Components/'.$this->parameters->name)) mkdir($root.'Components/'.$this->parameters->name);
        file_put_contents($root.'Components/'.$this->parameters->name.'/View.mpts', '<div>Lorem ipsum</div>');
        file_put_contents($root.'Components/'.$this->parameters->name.'/Controller.php', $this->generateController());
    }

    private function generateController()
    {
        $name=$this->parameters->name;
        return '<?php

namespace '.MAIN_NAMESPACE.'\Components\\'.$name.';

use Core\ComponentManager\ComponentController;
use Core\ComponentManager\ComponentManager;

class Controller extends ComponentController
{
    public function __construct($params)
    {
        $this->params = $params;
    }

    public static function DefinedParameters()
    {
        return [];
    }

    public function loadView()
    {
        $this->loadMPTS(__DIR__ . "/View.mpts");
    }
}
        ';
    }
}
