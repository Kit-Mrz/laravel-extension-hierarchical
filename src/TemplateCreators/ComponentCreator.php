<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\Component;
use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\ComponentAbstract;
use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\ComponentContract;
use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\ComponentImpl;
use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\ComponentInterface;
use Mrzkit\LaravelExtensionHierarchical\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateHandler;

class ComponentCreator implements TemplateCreatorContract
{
    /**
     * @var string
     */
    private $componentName;

    /**
     * @var TemplateHandler
     */
    private $templateHandler;

    public function __construct(string $componentName)
    {
        $this->componentName   = $componentName;
        $this->templateHandler = new TemplateHandler();
    }

    protected function createComponent() : TemplateContract
    {
        return new Component($this->componentName);
    }

    protected function createComponentAbstract() : TemplateContract
    {
        return new ComponentAbstract();
    }

    protected function createContract() : TemplateContract
    {
        return new ComponentContract();
    }

    protected function createComponentImpl() : TemplateContract
    {
        return new ComponentImpl($this->componentName);
    }

    protected function createComponentInterface() : TemplateContract
    {
        return new ComponentInterface($this->componentName);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandler->setTemplateContract($this->createComponent());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createComponentAbstract());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createContract());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createComponentImpl());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createComponentInterface());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        return $result;
    }
}
