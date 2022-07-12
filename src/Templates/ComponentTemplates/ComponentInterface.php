<?php

namespace Mrzkit\LaravelExtensionHierarchical\Templates\ComponentTemplates;

use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateObject;

class ComponentInterface implements TemplateHandleContract
{
    /**
     * @var string
     */
    private $componentName;

    public function __construct(string $componentName)
    {
        $this->componentName = $componentName;
    }

    /**
     * @return string
     */
    public function getComponentName() : string
    {
        return $this->componentName;
    }

    public function handle() : TemplateContract
    {
        $name = $this->getComponentName();


        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Components/{$name}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $name . 'Interface.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ComponentInterface.tpl';

        // 替换规则
        $replacementRules = [
            '/{{RNT}}/' => $name,
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

