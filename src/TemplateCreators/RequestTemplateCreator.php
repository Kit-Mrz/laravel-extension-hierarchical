<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateCreatorContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandlerContract;
use Mrzkit\LaravelExtensionHierarchical\Templates\RequestTemplates\BatchStoreRequest;
use Mrzkit\LaravelExtensionHierarchical\Templates\RequestTemplates\BatchUpdateRequest;
use Mrzkit\LaravelExtensionHierarchical\Templates\RequestTemplates\IndexRequest;
use Mrzkit\LaravelExtensionHierarchical\Templates\RequestTemplates\ManyRequest;
use Mrzkit\LaravelExtensionHierarchical\Templates\RequestTemplates\StoreRequest;
use Mrzkit\LaravelExtensionHierarchical\Templates\RequestTemplates\UpdateRequest;

class RequestTemplateCreator implements TemplateCreatorContract
{
    /**
     * @var string
     */
    private $controlName;

    /**
     * @var TemplateHandlerContract
     */
    private $templateHandlerContract;

    /**
     * @var TableInformationContract
     */
    private $tableInformationContract;

    public function __construct(string $controlName, TemplateHandlerContract $templateHandlerContract, TableInformationContract $tableInformationContract)
    {
        $this->controlName              = $controlName;
        $this->templateHandlerContract  = $templateHandlerContract;
        $this->tableInformationContract = $tableInformationContract;
    }

    protected function createIndexRequest() : TemplateHandleContract
    {
        return new IndexRequest($this->controlName);
    }

    protected function createManyRequest() : TemplateHandleContract
    {
        return new ManyRequest($this->controlName);
    }

    protected function createStoreRequest() : TemplateHandleContract
    {
        return new StoreRequest($this->controlName, $this->tableInformationContract);
    }

    protected function createUpdateRequest() : TemplateHandleContract
    {
        return new UpdateRequest($this->controlName, $this->tableInformationContract);
    }

    protected function createBatchStoreRequest() : TemplateHandleContract
    {
        return new BatchStoreRequest($this->controlName, $this->tableInformationContract);
    }

    protected function createBatchUpdateRequest() : TemplateHandleContract
    {
        return new BatchUpdateRequest($this->controlName, $this->tableInformationContract);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createIndexRequest()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createManyRequest()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createStoreRequest()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createUpdateRequest()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createBatchStoreRequest()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createBatchUpdateRequest()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        return $result;
    }

}
