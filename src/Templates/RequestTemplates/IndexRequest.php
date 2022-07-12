<?php

namespace Mrzkit\LaravelExtensionHierarchical\Templates\RequestTemplates;

use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\TableParser;
use Mrzkit\LaravelExtensionHierarchical\TemplateObject;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class IndexRequest implements TemplateHandleContract
{
    use TemplateTool;

    /**
     * @var string 控制器名称
     */
    private $controlName;

    /**
     * @var string 数据表名称
     */
    private $tableName;

    /**
     * @var TableParser 数据表解析器
     */
    private $tableParser;

    public function __construct(string $controlName)
    {
        $this->controlName = $controlName;
    }

    /**
     * @return string
     */
    public function getControlName() : string
    {
        return $this->controlName;
    }

    public function handle() : TemplateContract
    {
        $fullControlName = $this->getControlName();

        $controlName = static::processControlName($fullControlName);

        $namespacePath = static::processNamespacePath($fullControlName);

        $directoryPath = static::processDirectoryPath($fullControlName);

        //********************************************************

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Http/Controllers/{$directoryPath}{$controlName}Controls/Requests");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $controlName . 'IndexRequest.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/IndexRequest.tpl';

        // 替换规则
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/' => $namespacePath,
            '/{{RNT}}/'            => $controlName,
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
