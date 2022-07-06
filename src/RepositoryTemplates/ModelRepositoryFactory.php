<?php

namespace Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\CodeTemplate;
use Mrzkit\LaravelExtensionHierarchical\TableParser;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class ModelRepositoryFactory extends TemplateAbstract
{
    public function __construct(string $name)
    {
        $parser       = new TableParser($name, env('DB_PREFIX'));
        $codeTemplate = new CodeTemplate();

        $fillable         = $parser->getFieldString();
        $attributeComment = $parser->getFieldComment();

        $codeTplItem     = $codeTemplate->codeTplItem($parser->getUnionFields());
        $codeTplItemHump = $codeTemplate->codeTplItemHump($parser->getUnionFields());
        $codeTplItemIf   = $codeTemplate->codeTplItemIf($parser->getUnionFields());

        $pattern = '/^\w+$/';

        if ( !preg_match($pattern, $name, $matches)) {
            throw new InvalidArgumentException("Match fail : {$name}");
        }

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Repositories/{$name}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $name . 'RepositoryFactory.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ModelRepositoryFactory.tpl';

        // 替换规则
        $replacementRules = [
            '/{{RNT}}/' => $name,
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
