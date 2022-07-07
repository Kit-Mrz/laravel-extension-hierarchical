<?php

namespace Mrzkit\LaravelExtensionHierarchical\RouteTemplates;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Mrzkit\LaravelExtensionHierarchical\NewTableParser;
use Mrzkit\LaravelExtensionHierarchical\TableInformation;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\TemplateCreatorContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class Route extends TemplateAbstract implements TemplateCreatorContract
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

    /**
     * @var NewTableParser 数据表解析器
     */
    private $tableParser;

    public function __construct(string $controlName, string $tableName, string $tablePrefix = '')
    {
        if ( !$this->validateControlName($controlName)) {
            throw new \Exception("格式有误，参考格式: A.B 或 A.B.C ");
        }

        $this->controlName = $controlName;
        $this->tableName   = $tableName;
        $this->tablePrefix = $tablePrefix;
        $this->tableParser = new NewTableParser(new TableInformation($tableName, $tablePrefix));
    }

    /**
     * @return string
     */
    public function getControlName() : string
    {
        return $this->controlName;
    }

    /**
     * @return string
     */
    public function getTableName() : string
    {
        return $this->tableName;
    }

    /**
     * @return NewTableParser
     */
    public function getTableParser() : NewTableParser
    {
        return $this->tableParser;
    }

    /**
     * @var string
     */
    private $routePath;

    public function handle() : array
    {
        $fullControlName = $this->getControlName();

        if (strrpos($fullControlName, '.') !== false) {
            $firstControlName = substr($fullControlName, 0, strrpos($fullControlName, '.'));
        } else {
            $firstControlName = $fullControlName;
        }

        if (strripos($fullControlName, '.') !== false) {
            $controlName = substr($fullControlName, strripos($fullControlName, '.') + 1);
        } else {
            $controlName = $fullControlName;
        }

        if (strripos($fullControlName, '.') !== false) {
            $namespacePath = substr($fullControlName, 0, strripos($fullControlName, '.'));
            $namespacePath = str_replace('.', '\\', $namespacePath);
        } else {
            $namespacePath = $fullControlName;
        }

        $directoryPath = str_replace('\\', DIRECTORY_SEPARATOR, $namespacePath);
        $directoryPath = strlen($directoryPath) > 0 ? $directoryPath . DIRECTORY_SEPARATOR : $directoryPath;

        //********************************************************
        $controlPathName = Str::snake($controlName, '-');

        // ************************************

        $firstControlNameCamel = Str::camel($firstControlName);

        // 模板和写入文件都是自己
        $routePath = app()->basePath("routes") . '/' . $firstControlNameCamel . '.php';

        if ( !file_exists($routePath)) {
            $tpl = file_get_contents(__DIR__ . '/tpl/RouteEmpty.tpl');

            if (file_put_contents($routePath, $tpl) === false) {
                throw new InvalidArgumentException('创建路由文件失败:' . $routePath);
            }
        }
        // 设置路由
        $this->routePath = $routePath;

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

        $this->setForceCover($forceCover)
            ->setSaveDirectory($saveDirectory)
            ->setSaveFilename($saveFilename)
            ->setSourceTemplateFile($sourceTemplateFile)
            ->setReplacementRules($replacementRules)
            ->setReplacementRuleCallbacks($replacementRuleCallbacks);

        return [];
    }

    /**
     * @return string
     */
    public function getRoutePath() : string
    {
        return $this->routePath;
    }
}
