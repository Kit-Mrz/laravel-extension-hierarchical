<?php

namespace Mrzkit\LaravelExtensionHierarchical\Templates\UnitTestTemplates;

use Illuminate\Support\Str;
use Mrzkit\LaravelExtensionHierarchical\CodeTemplate;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateObject;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class UnitTest implements TemplateHandleContract
{
    use TemplateTool;

    /**
     * @var string
     */
    private $controlName;

    /**
     * @var TableInformationContract
     */
    private $tableInformationContract;

    private $codeTemplate;

    public function __construct(string $controlName, TableInformationContract $tableInformationContract)
    {
        $this->controlName              = $controlName;
        $this->tableInformationContract = $tableInformationContract;
        $this->codeTemplate             = new CodeTemplate($tableInformationContract);
    }

    /**
     * @return string
     */
    public function getControlName() : string
    {
        return $this->controlName;
    }

    /**
     * @return TableInformationContract
     */
    public function getTableInformationContract() : TableInformationContract
    {
        return $this->tableInformationContract;
    }

    /**
     * @return CodeTemplate
     */
    public function getCodeTemplate() : CodeTemplate
    {
        return $this->codeTemplate;
    }

    /**
     * @desc
     * @return string[]
     */
    public function getIgnoreFields() : array
    {
        return [
            "id", "createdBy", "createdAt", "updatedBy", "updatedAt", "deletedBy", "deletedAt",
            "id", "created_by", "created_at", "updated_by", "updated_at", "deleted_by", "deleted_at",
            "tenant_id", "tenantId",
        ];
    }

    public function handle() : TemplateContract
    {
        $fullControlName = $this->getControlName();

        $controlName = static::processControlName($fullControlName);

        $namespacePath = static::processNamespacePath($fullControlName);

        $directoryPath = static::processDirectoryPath($fullControlName);

        //********************************************************

        $unitTestStoreCodeTemplate = $this->getCodeTemplate()->getUnitTestStoreTpl($this->getIgnoreFields());
        $unitTestStoreSeedTpl      = $this->getCodeTemplate()->getUnitTestStoreSeedTpl($this->getIgnoreFields());

        //********************************************************

        // ??????????????????: true=??????,false=?????????
        $forceCover = false;

        // ????????????
        $saveDirectory = app()->basePath("tests/Feature/{$directoryPath}");

        $saveDirectory = rtrim($saveDirectory, '/');

        // ??????????????????
        $saveFilename = $saveDirectory . '/' . $controlName . 'ControllerTest.php';

        // ????????????
        $sourceTemplateFile = __DIR__ . '/tpl/UnitTest.tpl';

        // ????????????
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/'           => $namespacePath,
            '/{{RNT}}/'                      => $controlName,
            '/{{RNT_ROUTE_PATH}}/'           => Str::snake($controlName, '-'),
            '/{{UNIT_TEST_STORE_CODE}}/'     => $unitTestStoreCodeTemplate,
            '/{{UNIT_TEST_STORE_SEED_TPL}}/' => $unitTestStoreSeedTpl,
        ];

        // ????????????-??????
        $replacementRuleCallbacks = [
            // ??? $Good ????????? ->good
            '/(\\$)(' . $controlName . ')/' => function ($match){
                //$full   = $match[0];
                $symbol   = $match[1];
                $name     = $match[2];
                $humpName = strtolower(substr($name, 0, 1)) . substr($name, 1);

                return $symbol . $humpName;
            },
            // ??? ->Good ????????? ->good
            '/(\->)(' . $controlName . ')/' => function ($match){
                //$full   = $match[0];
                $symbol = $match[1];
                $name   = $match[2];

                $humpName = strtolower(substr($name, 0, 1)) . substr($name, 1);

                return $symbol . $humpName;
            },
            '/(\->)(\\$)(\w+)/'             => function ($match){
                $symbol   = $match[1];
                $name     = $match[3];
                $humpName = strtolower(substr($name, 0, 2)) . substr($name, 2);

                return $symbol . $humpName;
            },
        ];

        $templateObject = new TemplateObject();

        $templateObject->setForceCover($forceCover)
            ->setSaveDirectory($saveDirectory)
            ->setSaveFilename($saveFilename)
            ->setSourceTemplateFile($sourceTemplateFile)
            ->setReplacementRules($replacementRules)
            ->setReplacementRuleCallbacks($replacementRuleCallbacks);

        return $templateObject;
    }

}
