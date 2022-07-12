<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateCreatorContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandlerContract;
use Mrzkit\LaravelExtensionHierarchical\Templates\UnitTestTemplates\UnitTest;

class UnitTestTemplateCreator implements TemplateCreatorContract
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

    protected function createUnitTest() : TemplateHandleContract
    {
        return new UnitTest($this->controlName, $this->tableInformationContract);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandlerContract->setTemplateContract($this->createUnitTest()->handle());
        $result[]        = [
            'result'       => $templateHandler->getWriteResult(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        return $result;
    }
}
