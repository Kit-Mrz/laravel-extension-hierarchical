<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates\Model;
use Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates\ModelRepository;
use Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates\ModelRepositoryComplex;
use Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates\ModelRepositoryFactory;
use Mrzkit\LaravelExtensionHierarchical\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateHandler;

class RepositoryTemplateCreator implements TemplateCreatorContract
{
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

    public function __construct(string $tableName, string $tablePrefix = '', bool $shard = false)
    {
        $this->tableName       = $tableName;
        $this->tablePrefix     = $tablePrefix;
        $this->shard           = $shard;
        $this->templateHandler = new TemplateHandler();
    }

    protected function createModel() : TemplateContract
    {
        return new Model($this->tableName, $this->tablePrefix, $this->shard);
    }

    protected function createModelRepository() : TemplateContract
    {
        return new ModelRepository($this->tableName, $this->tablePrefix, $this->shard);
    }

    protected function createModelRepositoryComplex() : TemplateContract
    {
        return new ModelRepositoryComplex($this->tableName, $this->tablePrefix, $this->shard);
    }

    protected function createModelRepositoryFactory() : TemplateContract
    {
        return new ModelRepositoryFactory($this->tableName, $this->tablePrefix, $this->shard);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandler->setTemplateContract($this->createModel());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        $templateHandler = $this->templateHandler->setTemplateContract($this->createModelRepository());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        $templateHandler = $this->templateHandler->setTemplateContract($this->createModelRepositoryComplex());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        $templateHandler = $this->templateHandler->setTemplateContract($this->createModelRepositoryFactory());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        return $result;
    }

}
