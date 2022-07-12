<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateCreatorContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandlerContract;
use Mrzkit\LaravelExtensionHierarchical\Templates\ServiceTemplates\BusinessService;
use Mrzkit\LaravelExtensionHierarchical\Templates\ServiceTemplates\RenderService;
use Mrzkit\LaravelExtensionHierarchical\Templates\ServiceTemplates\Service;
use Mrzkit\LaravelExtensionHierarchical\Templates\ServiceTemplates\ServiceFactory;

class ServiceTemplateCreator implements TemplateCreatorContract
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

    protected function createService() : TemplateHandleContract
    {
        return new Service($this->controlName, $this->tableInformationContract);
    }

    protected function createBusinessService() : TemplateHandleContract
    {
        return new BusinessService($this->controlName, $this->tableInformationContract);
    }

    protected function createServiceFactory() : TemplateHandleContract
    {
        return new ServiceFactory($this->controlName, $this->tableInformationContract);
    }

    protected function createRenderService() : TemplateHandleContract
    {
        return new RenderService($this->controlName, $this->tableInformationContract);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createService()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createBusinessService()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createServiceFactory()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];
        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createRenderService()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        return $result;
    }

}
