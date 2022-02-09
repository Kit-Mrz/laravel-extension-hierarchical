<?php

namespace Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class ModelEvent extends TemplateAbstract
{
    public function __construct(string $name)
    {
        $pattern = '/^\w+$/';

        if ( !preg_match($pattern, $name, $matches)) {
            throw new InvalidArgumentException("Match fail : {$name}");
        }

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Repositories/{$name}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $name . 'Event.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ModelEvent.tpl';

        // 替换规则
        $replacementRules = [
            '/{{RNT}}/' => $name,
        ];

        // 替换规则-回调
        $replacementRuleCallbacks = [
            '/(\->)(\\$)(\w+)/' => function ($match){
                $symbol   = $match[1];
                $name     = $match[3];
                $humpName = strtolower(substr($name, 0, 2)) . substr($name, 2);

                return $symbol . $humpName;
            },
            '/(\\$)(\w+)/'      => function ($match){
                $symbolDollar = $match[1];
                $name         = $match[2];
                $humpName     = strtolower(substr($name, 0, 1)) . substr($name, 1);

                return $symbolDollar . $humpName;
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
