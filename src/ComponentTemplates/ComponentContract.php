<?php

namespace Mrzkit\LaravelExtensionHierarchical\ComponentTemplates;

use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class ComponentContract extends TemplateAbstract
{
    public function __construct(string $name)
    {
        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Components/");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $name . '.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ComponentContract.tpl';

        // 替换规则
        $replacementRules = [

        ];

        // 替换规则-回调
        $replacementRuleCallbacks = [

        ];

        $this->setForceCover($forceCover)
            ->setSaveDirectory($saveDirectory)
            ->setSaveFilename($saveFilename)
            ->setSourceTemplateFile($sourceTemplateFile)
            ->setReplacementRules($replacementRules)
            ->setReplacementRuleCallbacks($replacementRuleCallbacks);
    }
}