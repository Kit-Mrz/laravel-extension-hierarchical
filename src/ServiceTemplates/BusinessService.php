<?php

namespace Mrzkit\LaravelExtensionHierarchical\ServiceTemplates;

use Mrzkit\LaravelExtensionHierarchical\NewTableParser;
use Mrzkit\LaravelExtensionHierarchical\TableInformation;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\TemplateCreatorContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class BusinessService extends TemplateAbstract implements TemplateCreatorContract
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

    /**
     * @desc
     * @return string[]
     */
    public function getIgnoreFields() : array
    {
        return [
            "id", "createdBy", "createdAt", "updatedBy", "updatedAt", "deletedBy", "deletedAt",
            "id", "created_by", "created_at", "updated_by", "updated_at", "deleted_by", "deleted_at",
        ];
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

        $parser = $this->getTableParser();

        $repositoryName = $parser->getRenderTableName();

        $updateCodeString = $parser->updateCodeTemplate($this->getIgnoreFields());

        // 数据仓库名称
        $repository = "{$repositoryName}Repository";

        //********************************************************

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Services/{$directoryPath}{$controlName}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $controlName . 'BusinessService.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/BusinessService.tpl';

        // 替换规则
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/' => $namespacePath,
            '/{{RNT}}/'            => $controlName,
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
