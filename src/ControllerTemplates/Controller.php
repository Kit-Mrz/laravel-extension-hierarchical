<?php

namespace Mrzkit\LaravelExtensionHierarchical\ControllerTemplates;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class Controller extends TemplateAbstract
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
        $saveDirectory = app()->basePath("app/Http/Controllers/{$namespacePath}/{$controlName}Controls");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $controlName . 'Controller.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/Controller.tpl';

        // 替换规则
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/' => $namespacePath,
            '/{{RNT}}/'            => $controlName,
        ];

        // 替换规则-回调
        $replacementRuleCallbacks = [
            // 将 $Good 替换为 ->good
            '/(\\$)(' . $controlName . ')/' => function ($match){
                //$full   = $match[0];
                $symbol   = $match[1];
                $name     = $match[2];
                $humpName = strtolower(substr($name, 0, 1)) . substr($name, 1);

                return $symbol . $humpName;
            },
            // 将 ->Good 替换为 ->good
            '/(\->)(' . $controlName . ')/' => function ($match){
                //$full   = $match[0];
                $symbol = $match[1];
                $name   = $match[2];

                $humpName = strtolower(substr($name, 0, 1)) . substr($name, 1);

                return $symbol . $humpName;
            },
            '/(\->)(\\$)(\w+)/'             => function ($match){
                $symbol   = $match[1];
                $name     = $match[3];
                $humpName = strtolower(substr($name, 0, 2)) . substr($name, 2);

                return $symbol . $humpName;
            },
        ];

        $this->setForceCover($forceCover)
            ->setSaveDirectory($saveDirectory)
            ->setSaveFilename($saveFilename)
            ->setSourceTemplateFile($sourceTemplateFile)
            ->setReplacementRules($replacementRules)
            ->setReplacementRuleCallbacks($replacementRuleCallbacks);
    }

}
