<?php

namespace Mrzkit\LaravelExtensionHierarchical\TemplateCreators;

use Mrzkit\LaravelExtensionHierarchical\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateHandler;
use Mrzkit\LaravelExtensionHierarchical\UnitTestTemplates\UnitTest;

class UnitTestTemplateCreator implements TemplateCreatorContract
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

    protected function createUnitTest() : TemplateContract
    {
        return new UnitTest($this->controlName, $this->tableName, $this->tablePrefix);
    }

    public function handle() : array
    {
        $result = [];

        $templateHandler = $this->templateHandler->setTemplateContract($this->createUnitTest());
        $result[]        = [
            'result'       => $templateHandler->execute(),
            'saveFilename' => $templateHandler->getTemplateContract()->getSaveFilename(),
        ];

        return $result;
    }
}
