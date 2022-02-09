<?php

namespace Mrzkit\LaravelExtensionHierarchical\ServiceTemplates;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class BusinessService extends TemplateAbstract
{
    public function __construct(string $name)
    {
        $replaceTarget = str_replace('/', '.', $name);

        $pattern = '/^\w+\\.\w+$/';

        if ( !preg_match($pattern, $replaceTarget, $matches)) {
            throw new InvalidArgumentException("Match fail : {$replaceTarget}");
        }

        list($namespacePath, $controlName) = explode('.', $replaceTarget);

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Services/{$namespacePath}/{$controlName}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $controlName . 'BusinessService.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/BusinessService.tpl';

        // 替换规则
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/' => $namespacePath,
            '/{{RNT}}/'            => $controlName,
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
