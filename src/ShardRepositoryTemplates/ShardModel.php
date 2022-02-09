<?php

namespace Mrzkit\LaravelExtensionHierarchical\ShardRepositoryTemplates;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\TableParser;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class ShardModel extends TemplateAbstract
{
    public function __construct(string $name)
    {
        $parser           = new TableParser($name, env('DB_PREFIX'));
        $fillable         = $parser->getFieldString();
        $attributeComment = $parser->getFieldComment();

        $replaceTarget = str_replace('/', '.', $name);

        $pattern = '/^\w+$/';

        if ( !preg_match($pattern, $replaceTarget, $matches)) {
            throw new InvalidArgumentException("Match fail : {$replaceTarget}");
        }

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Repositories/{$name}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $name . '.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ShardModel.tpl';

        // 替换规则
        $replacementRules = [
            '/{{RNT}}/'               => $name,
            '/{{FILLABLE}}/'          => $fillable,
            '/{{ATTRIBUTE_COMMENT}}/' => $attributeComment,
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
