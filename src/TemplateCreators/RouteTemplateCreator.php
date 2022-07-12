<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateCreatorContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandlerContract;
use Mrzkit\LaravelExtensionHierarchical\Templates\RouteTemplates\Route;
use Mrzkit\LaravelExtensionHierarchical\Templates\RouteTemplates\RouteReplace;

class RouteTemplateCreator implements TemplateCreatorContract
{
    /**
     * @var string
     */
    private $controlName;

    /**
     * @var TemplateHandlerContract
     */
    private $templateHandlerContract;

    public function __construct(string $controlName, TemplateHandlerContract $templateHandlerContract)
    {
        $this->controlName             = $controlName;
        $this->templateHandlerContract = $templateHandlerContract;
    }

    protected function createRoute() : TemplateHandleContract
    {
        return new Route($this->controlName);
    }

    protected function createRouteReplace(string $content) : TemplateHandleContract
    {
        return new RouteReplace($this->controlName, $content,);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createRoute()->handle());

        $replaceString = $templateHandler->getReplaceResult();

        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createRouteReplace($replaceString)->handle());

        $result[] = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        return $result;
    }

}
