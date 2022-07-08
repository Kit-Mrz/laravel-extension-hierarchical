<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\BatchStoreRequest;
use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\BatchUpdateRequest;
use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\IndexRequest;
use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\ManyRequest;
use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\StoreRequest;
use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\UpdateRequest;
use Mrzkit\LaravelExtensionHierarchical\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateHandler;

class RequestTemplateCreator implements TemplateCreatorContract
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

    protected function createIndexRequest() : TemplateContract
    {
        return new IndexRequest($this->controlName, $this->tableName, $this->tablePrefix);
    }

    protected function createManyRequest() : TemplateContract
    {
        return new ManyRequest($this->controlName, $this->tableName, $this->tablePrefix);
    }

    protected function createStoreRequest() : TemplateContract
    {
        return new StoreRequest($this->controlName, $this->tableName, $this->tablePrefix);
    }

    protected function createUpdateRequest() : TemplateContract
    {
        return new UpdateRequest($this->controlName, $this->tableName, $this->tablePrefix);
    }

    protected function createBatchStoreRequest() : TemplateContract
    {
        return new BatchStoreRequest($this->controlName, $this->tableName, $this->tablePrefix);
    }

    protected function createBatchUpdateRequest() : TemplateContract
    {
        return new BatchUpdateRequest($this->controlName, $this->tableName, $this->tablePrefix);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandler->setTemplateContract($this->createIndexRequest());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createManyRequest());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createStoreRequest());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createUpdateRequest());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createBatchStoreRequest());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandler->setTemplateContract($this->createBatchUpdateRequest());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        return $result;
    }

}
