<?php

namespace Mrzkit\LaravelExtensionHierarchical\Templates\ServiceTemplates;

use Mrzkit\LaravelExtensionHierarchical\CodeTemplate;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\TableInformation;
use Mrzkit\LaravelExtensionHierarchical\TableParser;
use Mrzkit\LaravelExtensionHierarchical\TemplateObject;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class RenderService implements TemplateHandleContract
{
    use TemplateTool;

    /**
     * @var string 控制器名称
     */
    private $controlName;

    /**
     * @var TableInformationContract
     */
    private $tableInformationContract;

    /**
     * @var CodeTemplate
     */
    private $codeTemplate;

    public function __construct(string $controlName, TableInformationContract $tableInformationContract)
    {
        if ( !$this->validateControlName($controlName)) {
            throw new \Exception("格式有误，参考格式: A.B 或 A.B.C ");
        }

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
        ];
    }

    public function handle() : TemplateContract
    {
        $fullControlName = $this->getControlName();

        $controlName = $this->processControlName($fullControlName);

        $namespacePath = $this->processNamespacePath($fullControlName);

        $directoryPath = $this->processDirectoryPath($fullControlName);

        //********************************************************
        $repositoryName        = $this->getCodeTemplate()->getRenderTableName();

        // 数据仓库名称
        $repository = "{$repositoryName}Repository";

        //********************************************************

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Services/{$directoryPath}{$controlName}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $controlName . 'RenderService.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/RenderService.tpl';

        // 替换规则
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/'  => $namespacePath,
            '/{{RNT}}/'             => $controlName,
            '/{{REPOSITORY}}/'      => $repository,
            '/{{REPOSITORY_NAME}}/' => $repositoryName,
        ];

        // 替换规则-回调
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
