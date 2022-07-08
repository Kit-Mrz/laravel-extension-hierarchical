<?php

namespace Mrzkit\LaravelExtensionHierarchical\UnitTestTemplates;

use Illuminate\Support\Str;
use Mrzkit\LaravelExtensionHierarchical\NewTableParser;
use Mrzkit\LaravelExtensionHierarchical\TableInformation;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\TemplateCreatorContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class UnitTest extends TemplateAbstract implements TemplateCreatorContract
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
     * @return string
     */
    public function getTablePrefix() : string
    {
        return $this->tablePrefix;
    }

    public function handle() : array
    {
        $fullControlName = $this->getControlName();

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

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("tests/Feature/{$directoryPath}{$controlName}Controls");
        $saveDirectory = app()->basePath("tests/Feature/{$directoryPath}");

        $saveDirectory = rtrim($saveDirectory, '/');

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $controlName . 'ControllerTest.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/UnitTest.tpl';

        // 替换规则
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/' => $namespacePath,
            '/{{RNT}}/'            => $controlName,
            '/{{RNT_ROUTE_PATH}}/' => Str::snake($controlName, '-'),
        ];

        // 替换规则-回调
        $replacementRuleCallbacks = [
            // 将 $Good 替换为 ->good
            '/(\\$)(' . $controlName . ')/' => function ($match){
                //$full   = $match[0];
                $symbol   = $match[1];
                $name     = $match[2];
                $humpName = strtolower(substr($name, 0, 1)) . substr($name, 1);

                return $symbol . $humpName;
            },
            // 将 ->Good 替换为 ->good
            '/(\->)(' . $controlName . ')/' => function ($match){
                //$full   = $match[0];
                $symbol = $match[1];
                $name   = $match[2];

                $humpName = strtolower(substr($name, 0, 1)) . substr($name, 1);

                return $symbol . $humpName;
            },
            '/(\->)(\\$)(\w+)/'             => function ($match){
                $symbol   = $match[1];
                $name     = $match[3];
                $humpName = strtolower(substr($name, 0, 2)) . substr($name, 2);

                return $symbol . $humpName;
            },
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
