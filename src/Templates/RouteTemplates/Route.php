<?php

namespace Mrzkit\LaravelExtensionHierarchical\Templates\RouteTemplates;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandleContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateObject;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class Route implements TemplateHandleContract
{
    use TemplateTool;

    /**
     * @var string 控制器名称
     */
    private $controlName;

    /**
     * @var string 数据表名称
     */
    private $tableName;

    public function __construct(string $controlName)
    {
        $this->controlName = $controlName;
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

        // ************************************

        // 模板和写入文件都是自己
        $routePath = app()->basePath("routes") . '/' . $firstControlNameCamel . '.php';

        if ( !file_exists($routePath)) {
            $tpl = file_get_contents(__DIR__ . '/tpl/RouteEmpty.tpl');

            if (file_put_contents($routePath, $tpl) === false) {
                throw new InvalidArgumentException('创建路由文件失败:' . $routePath);
            }
        }

        // 读取路由文件
        $content = file_get_contents($routePath);

        // 如果有此关键字，则不添加
        $force = true;
        if (preg_match("/{$controlName}/", $content)) {
            $force = false;
        }

        // 中间件
        $authMiddleware = $controlName == 'AdminSystem' ? 'auth:adm' : 'auth:api';

        //********************************************************

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = $force;

        // 保存目录
        $saveDirectory = app()->basePath("routes");

        // 保存文件名称
        $saveFilename = '/tmp/not-need.log';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/Route.tpl';

        // 替换规则
        $replacementRules = [
            '/{{AUTH_MIDDLEWARE}}/'  => $authMiddleware,
            '/{{NAMESPACE_PATH}}/'   => $namespacePath,
            '/{{RNT}}/'              => $controlName,
            '/{{LOWER_ROUTE_NAME}}/' => $controlPathName,
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
