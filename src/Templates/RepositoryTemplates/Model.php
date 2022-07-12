<?php

namespace Mrzkit\LaravelExtensionHierarchical\Templates\RepositoryTemplates;

use Mrzkit\LaravelExtensionHierarchical\CodeTemplate;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateObject;

class Model implements TemplateHandleContract
{
    /**
     * @var TableInformationContract
     */
    private $tableInformationContract;

    /**
     * @var CodeTemplate
     */
    private $codeTemplate;

    public function __construct(TableInformationContract $tableInformationContract)
    {
        $this->tableInformationContract = $tableInformationContract;

        $this->codeTemplate = new CodeTemplate($tableInformationContract);
    }

    /**
     * @return TableInformationContract
     */
    public function getTableInformationContract()
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

    public function handle() : TemplateContract
    {
        $codeTemplate = $this->getCodeTemplate();

        $tableInformationContract = $this->getTableInformationContract();

        $tableName = $codeTemplate->getRenderTableName();

        //********************************************************

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Repositories/{$tableName}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $tableName . '.php';

        // 模板文件
        if ($tableInformationContract->getTableShard()) {
            $sourceTemplateFile = __DIR__ . '/stpl/Model.tpl';
        } else {
            $sourceTemplateFile = __DIR__ . '/tpl/Model.tpl';
        }

        // 替换规则
        $replacementRules = [
            '/{{RNT}}/'              => $tableName,
            '/{{FILL_ABLE_TPL}}/'    => $codeTemplate->getFillAbleTpl(),
            '/{{FIELDS_TPL}}/'       => $codeTemplate->getFieldsTpl(),
            '/{{MAX_SHARD_COUNT}}/'  => $tableInformationContract->getMaxShardCount(),
            '/{{SHARD_CONFIG_TPL}}/' => $codeTemplate->getShardConfigTpl(),
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
