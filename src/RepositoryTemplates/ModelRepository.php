<?php

namespace Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\CodeTemplate;
use Mrzkit\LaravelExtensionHierarchical\TableParser;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class ModelRepository extends TemplateAbstract
{
    public function __construct(string $name)
    {
        $parser       = new TableParser($name, env('DB_PREFIX'));
        $codeTemplate = new CodeTemplate();

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
        $saveFilename = $saveDirectory . '/' . $name . 'Repository.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ModelRepository.tpl';

        // 替换规则
        $replacementRules = [
            '/{{RNT}}/'                => $name,
            '/{{CODE_TPL_ITEM}}/'      => $codeTplItem,
            '/{{CODE_TPL_ITEM_HUMP}}/' => $codeTplItemHump,
            '/{{CODE_TPL_ITEM_IF}}/'   => $codeTplItemIf,
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
