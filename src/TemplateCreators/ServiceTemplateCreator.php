<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\BusinessService;
use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\RenderService;
use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\Service;
use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\ServiceFactory;
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
     * @var bool
     */
    private $shard;

    /**
     * @var TemplateHandler
     */
    private $templateHandler;

    public function __construct(string $controlName, string $tableName, string $tablePrefix = '', bool $shard = false)
    {
        $this->controlName     = $controlName;
        $this->tableName       = $tableName;
        $this->tablePrefix     = $tablePrefix;
        $this->shard           = $shard;
        $this->templateHandler = new TemplateHandler();
    }

    protected function createService() : TemplateContract
    {
        return new Service($this->controlName, $this->tableName, $this->tablePrefix, $this->shard);
    }

    protected function createBusinessService() : TemplateContract
    {
        return new BusinessService($this->controlName, $this->tableName, $this->tablePrefix, $this->shard);
    }

    protected function createServiceFactory() : TemplateContract
    {
        return new ServiceFactory($this->controlName, $this->tableName, $this->tablePrefix, $this->shard);
    }

    protected function createRenderService() : TemplateContract
    {
        return new RenderService($this->controlName, $this->tableName, $this->tablePrefix, $this->shard);
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
