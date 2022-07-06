<?php

namespace Mrzkit\LaravelExtensionHierarchical\ShardService;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class ShardServiceFactory extends TemplateAbstract
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
        $saveFilename = $saveDirectory . '/' . $controlName . 'ServiceFactory.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ShardServiceFactory.tpl';

        // 替换规则
        $replacementRules = [
            '/{{RNT}}/'            => $controlName,
            '/{{NAMESPACE_PATH}}/' => $namespacePath,
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
