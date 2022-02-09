<?php

namespace Mrzkit\LaravelExtensionHierarchical\RouteTemplates;

use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;

class Route extends TemplateAbstract
{
    /**
     * @var string
     */
    private $routePath;

    public function __construct(string $name)
    {
        $replaceTarget = str_replace('/', '.', $name);

        $pattern = '/^\w+\\.\w+$/';

        if ( !preg_match($pattern, $replaceTarget, $matches)) {
            throw new InvalidArgumentException("Match fail : {$replaceTarget}");
        }

        list($routeFileName, $routeName) = explode('.', $replaceTarget);

        $saveTo = app()->basePath("routes");

        // 替换为小驼峰: AminRoute => adminRoute
        $route = strtolower(substr($routeFileName, 0, 1)) . substr($routeFileName, 1);
        // 模板和写入文件都是自己
        $routePath = $saveTo . '/' . $route . '.php';
        if ( !file_exists($routePath)) {
            throw new InvalidArgumentException('路由文件不存在:' . $routePath);
        }
        $this->setRoutePath($routePath);

        // 读取路由文件
        $content = file_get_contents($routePath);
        // 如果有此关键字，则不添加
        $force = true;
        if (preg_match("/{$routeName}/", $content)) {
            $force = false;
        }
        $lowerRouteName = strtolower($routeName);

        // 中间件
        $authMiddleware = $route == 'adminSystem' ? 'auth:adm' : 'auth:api';

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
            '/{{NAMESPACE_PATH}}/'   => $routeFileName,
            '/{{RNT}}/'              => $routeName,
            '/{{LOWER_ROUTE_NAME}}/' => $lowerRouteName,
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

    /**
     * @return string
     */
    public function getRoutePath() : string
    {
        return $this->routePath;
    }

    /**
     * @param string $routePath
     * @return Route
     */
    public function setRoutePath(string $routePath) : Route
    {
        $this->routePath = $routePath;

        return $this;
    }
}
