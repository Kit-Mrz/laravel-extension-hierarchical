<?php

namespace Mrzkit\LaravelExtensionHierarchical\RouteTemplates;

use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class RouteReplace extends TemplateAbstract
{
    public function __construct(string $name, string $routePath, string $resultContent)
    {
        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = true;

        // 保存目录
        $saveDirectory = app()->basePath("app/Components/{$name}");

        // 保存文件名称
        $saveFilename = $routePath;

        // 模板文件
        $sourceTemplateFile = $routePath;

        // 替换规则
        $replacementRules = [
            '/\\/\\/{{HERE}}/' => $resultContent,
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
