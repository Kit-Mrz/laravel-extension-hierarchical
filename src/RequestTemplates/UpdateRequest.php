<?php

namespace Mrzkit\LaravelExtensionHierarchical\RequestTemplates;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\TableParser;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class UpdateRequest extends TemplateAbstract
{
    public function __construct(string $name)
    {
        $replaceTarget = str_replace('/', '.', $name);

        $pattern = '/^\w+\\.\w+$/';

        if ( !preg_match($pattern, $replaceTarget, $matches)) {
            throw new InvalidArgumentException("Match fail : {$replaceTarget}");
        }

        list($namespacePath, $controlName) = explode('.', $replaceTarget);

        // --------------------
        // 如果结尾的字符是 y
        if (substr($controlName, -1, 1) == 'y') {
            // 替换为 ies
            $tableName = substr($controlName, 0, strlen($controlName) - 1) . 'ies';
        } else {
            // 拼接 s
            $tableName = $controlName . 's';
        }

        $parser              = new TableParser($tableName, env('DB_PREFIX'));
        $ruleUpdateString    = $parser->getSimpleRules();
        $messageUpdateString = $parser->getSimpleMessages();

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Http/Controllers/{$namespacePath}/{$controlName}Controls/Requests");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $controlName . 'UpdateRequest.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/UpdateRequest.tpl';

        // 替换规则
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/' => $namespacePath,
            '/{{RNT}}/'            => $controlName,
            '/{{RULE_STRING}}/'    => $ruleUpdateString,
            '/{{MESSAGE_STRING}}/' => $messageUpdateString,
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