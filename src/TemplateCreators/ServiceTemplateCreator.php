<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\BusinessService;
use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\Service;
use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\ServiceFactory;
use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\RenderService;
use Mrzkit\LaravelExtensionHierarchical\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateHandler;

class ServiceTemplateCreator implements TemplateCreatorContract
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

    protected function createService() : TemplateContract
    {
        return new Service($this->controlName, $this->tableName, $this->tablePrefix);
    }

    protected function createBusinessService() : TemplateContract
    {
        return new BusinessService($this->controlName, $this->tableName, $this->tablePrefix);
    }

    protected function createServiceFactory() : TemplateContract
    {
        return new ServiceFactory($this->controlName, $this->tableName, $this->tablePrefix);
    }

    protected function createRenderService() : TemplateContract
    {
        return new RenderService($this->controlName, $this->tableName, $this->tablePrefix);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandler->setTemplateContract($this->createService());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createBusinessService());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createServiceFactory());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createRenderService());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];


        return $result;
    }

}
