<?php

namespace Mrzkit\LaravelExtensionHierarchical\ComponentTemplates;

use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\TemplateCreatorContract;

class ComponentContract extends TemplateAbstract implements TemplateCreatorContract
{

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name = 'ComponentContract')
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
        $saveDirectory = app()->basePath("app/Components/");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $name . '.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ComponentContract.tpl';

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

        return [];
    }
}
