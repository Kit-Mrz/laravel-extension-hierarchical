<?php

namespace Mrzkit\LaravelExtensionHierarchical\ServiceTemplates;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\CodeTemplate;
use Mrzkit\LaravelExtensionHierarchical\TableParser;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class Service extends TemplateAbstract
{
    public function __construct(string $name)
    {
        $replaceTarget = str_replace('/', '.', $name);

        $pattern = '/^\w+\\.\w+$/';

        if ( !preg_match($pattern, $replaceTarget, $matches)) {
            throw new InvalidArgumentException("Match fail : {$replaceTarget}");
        }

        list($namespacePath, $controlName) = explode('.', $replaceTarget);

        // 如果结尾的字符是 y
        if (substr($controlName, -1, 1) == 'y') {
            // 替换为 ies
            $repositoryName = substr($controlName, 0, strlen($controlName) - 1) . 'ies';
        } else {
            // 拼接 s
            $repositoryName = $controlName . 's';
        }
        // 数据仓库名称
        $repository = "{$repositoryName}Repository";

        $parser = new TableParser($repositoryName, env('DB_PREFIX'));

        $fillable   = $parser->getFieldString();
        $humpFields = $parser->getHumpFieldsString();

        $codeTemplate = new CodeTemplate();

        $codeTplUpdate = $codeTemplate->codeTplUpdate($parser->getUnionFields());
        $codeTplStore  = $codeTemplate->codeTplStore($parser->getUnionFields());
        $codeTplIndex  = $codeTemplate->codeTplIndex($parser->getUnionFields());

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Services/{$namespacePath}/{$controlName}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $controlName . 'Service.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/Service.tpl';

        // 替换规则
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/'  => $namespacePath,
            '/{{RNT}}/'             => $controlName,
            '/{{FILLABLE}}/'        => $fillable,
            '/{{HUMP_FIELDS}}/'     => $humpFields,
            '/{{CODE_TPL_UPDATE}}/' => $codeTplUpdate,
            '/{{CODE_TPL_STORE}}/'  => $codeTplStore,
            '/{{CODE_TPL_INDEX}}/'  => $codeTplIndex,
            '/{{REPOSITORY}}/'      => $repository,
            '/{{REPOSITORY_NAME}}/' => $repositoryName,
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
