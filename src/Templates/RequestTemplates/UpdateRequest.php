<?php

namespace Mrzkit\LaravelExtensionHierarchical\Templates\RequestTemplates;

use Mrzkit\LaravelExtensionHierarchical\CodeTemplate;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateObject;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class UpdateRequest implements TemplateHandleContract
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

        $requestTemplateRenderContract = $this->getCodeTemplate()->getRequestUpdateTpl($this->getIgnoreFields());

        //********************************************************

        // ??????????????????: true=??????,false=?????????
        $forceCover = false;

        // ????????????
        $saveDirectory = app()->basePath("app/Http/Controllers/{$directoryPath}{$controlName}Controls/Requests");

        // ??????????????????
        $saveFilename = $saveDirectory . '/' . $controlName . 'UpdateRequest.php';

        // ????????????
        $sourceTemplateFile = __DIR__ . '/tpl/UpdateRequest.tpl';

        // ????????????
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/'             => $namespacePath,
            '/{{RNT}}/'                        => $controlName,
            '/{{REQUEST_UPDATE_RULE_TPL}}/'    => $requestTemplateRenderContract->getRuleString(),
            '/{{REQUEST_UPDATE_MESSAGE_TPL}}/' => $requestTemplateRenderContract->getMessageString(),
        ];

        // ????????????-??????
        $replacementRuleCallbacks = [

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
