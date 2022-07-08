<?php

namespace Mrzkit\LaravelExtensionHierarchical\SupportTemplates;

use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\TemplateCreatorContract;

class FormRequest extends TemplateAbstract implements TemplateCreatorContract
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    public function handle() : array
    {
        $name = $this->getName();

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Supports/Cores/");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $name . '.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/FormRequest.tpl';

        // 替换规则
        $replacementRules = [
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
