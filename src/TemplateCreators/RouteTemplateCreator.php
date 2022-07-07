<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\RouteTemplates\Route;
use Mrzkit\LaravelExtensionHierarchical\RouteTemplates\RouteReplace;
use Mrzkit\LaravelExtensionHierarchical\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateHandler;

class RouteTemplateCreator implements TemplateCreatorContract
{
    /**
     * @var string
     */
    private $controlName;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $tablePrefix;

    /**
     * @var TemplateHandler
     */
    private $templateHandler;

    public function __construct(string $controlName, string $tableName, string $tablePrefix = '')
    {
        $this->controlName     = $controlName;
        $this->tableName       = $tableName;
        $this->tablePrefix     = $tablePrefix;
        $this->templateHandler = new TemplateHandler();
    }

    protected function createRoute() : TemplateContract
    {
        return new Route($this->controlName, $this->tableName, $this->tablePrefix);
    }

    protected function createRouteReplace(string $content) : TemplateContract
    {
        return new RouteReplace($this->controlName, $content, $this->tableName, $this->tablePrefix);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandler->setTemplateContract($this->createRoute());

        $replaceString = $templateHandler->executeReplace();

        $templateHandler = $this->templateHandler->setTemplateContract($this->createRouteReplace($replaceString));

        $result[] = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        return $result;
    }

}
