<?php

namespace Mrzkit\LaravelExtensionHierarchical\Templates\RouteTemplates;

use Illuminate\Support\Str;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateObject;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class RouteReplace implements TemplateHandleContract
{
    use TemplateTool;

    /**
     * @var string
     */
    private $controlName;

    /**
     * @var string
     */
    private $content;

    public function __construct(string $controlName, string $content)
    {
        $this->controlName = $controlName;
        $this->content     = $content;
    }

    /**
     * @return string
     */
    public function getControlName() : string
    {
        return $this->controlName;
    }

    public function handle() : TemplateContract
    {
        $fullControlName = $this->getControlName();

        $firstControlName = $this->processFirstControlName($fullControlName);

        $controlName = $this->processControlName($fullControlName);

        $namespacePath = $this->processNamespacePath($fullControlName);

        $directoryPath = $this->processDirectoryPath($fullControlName);

        $controlPathName = Str::snake($controlName, '-');

        $firstControlNameCamel = Str::camel($firstControlName);

        //*******************************************************

        // 模板和写入文件都是自己
        $routePath = app()->basePath("routes") . '/' . $firstControlNameCamel . '.php';

        if ( !file_exists($routePath)) {
            throw new \InvalidArgumentException('路由文件不存在:' . $routePath);
        }

        // 读取路由文件
        $content = file_get_contents($routePath);

        // 如果有此关键字，则不添加
        $force = true;
        if (preg_match("/{$controlName}/", $content)) {
            $force = false;
        }

        //********************************************************

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = $force;

        // 保存目录
        $saveDirectory = app()->basePath("app/Components/{$firstControlNameCamel}");

        // 保存文件名称
        $saveFilename = $routePath;

        // 模板文件
        $sourceTemplateFile = $routePath;

        // 替换规则
        $replacementRules = [
            '/\\/\\/{{HERE}}/' => $this->content,
        ];

        // 替换规则-回调
        $replacementRuleCallbacks = [

        ];

        $templateObject = new TemplateObject();

        $templateObject->setForceCover($forceCover)
            ->setSaveDirectory($saveDirectory)
            ->setSaveFilename($saveFilename)
            ->setSourceTemplateFile($sourceTemplateFile)
            ->setReplacementRules($replacementRules)
            ->setReplacementRuleCallbacks($replacementRuleCallbacks);

        return $templateObject;
    }
}
