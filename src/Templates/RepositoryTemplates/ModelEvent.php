<?php

namespace Mrzkit\LaravelExtensionHierarchical\Templates\RepositoryTemplates;

use Mrzkit\LaravelExtensionHierarchical\CodeTemplate;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\TableInformation;
use Mrzkit\LaravelExtensionHierarchical\TemplateObject;

class ModelEvent implements TemplateHandleContract
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
        $tableName = $this->getCodeTemplate()->getRenderTableName();

        //********************************************************

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Repositories/{$tableName}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $tableName . 'Event.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ModelEvent.tpl';

        // 替换规则
        $replacementRules = [
            '/{{RNT}}/' => $tableName,
        ];

        // 替换规则-回调
        $replacementRuleCallbacks = [
            '/(\->)(\\$)(\w+)/' => function ($match){
                $symbol    = $match[1];
                $tableName = $match[3];
                $humpName  = strtolower(substr($tableName, 0, 2)) . substr($tableName, 2);

                return $symbol . $humpName;
            },
            '/(\\$)(\w+)/'      => function ($match){
                $symbolDollar = $match[1];
                $tableName    = $match[2];
                $humpName     = strtolower(substr($tableName, 0, 1)) . substr($tableName, 1);

                return $symbolDollar . $humpName;
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