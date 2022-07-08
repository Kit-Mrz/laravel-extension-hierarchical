<?php

namespace Mrzkit\LaravelExtensionHierarchical\ServiceTemplates;

use Mrzkit\LaravelExtensionHierarchical\NewTableParser;
use Mrzkit\LaravelExtensionHierarchical\TableInformation;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\TemplateCreatorContract;
use Mrzkit\LaravelExtensionHierarchical\TemplateTool;

class Service extends TemplateAbstract implements TemplateCreatorContract
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
     * @var bool
     */
    private $shard;

    /**
     * @var NewTableParser 数据表解析器
     */
    private $tableParser;

    public function __construct(string $controlName, string $tableName, string $tablePrefix = '', bool $shard = false)
    {
        if ( !$this->validateControlName($controlName)) {
            throw new \Exception("格式有误，参考格式: A.B 或 A.B.C ");
        }

        $this->controlName = $controlName;
        $this->tableName   = $tableName;
        $this->tablePrefix = $tablePrefix;
        $this->shard       = $shard;
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
     * @return bool
     */
    public function isShard() : bool
    {
        return $this->shard;
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
        $storeCodeString  = $parser->storeCodeTemplate($this->getIgnoreFields());

        // 数据仓库名称
        $repository = "{$repositoryName}Repository";

        //********************************************************

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Services/{$directoryPath}{$controlName}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $controlName . 'Service.php';

        // 模板文件
        if ($this->isShard()) {
            $sourceTemplateFile = __DIR__ . '/stpl/Service.tpl';
        } else {
            $sourceTemplateFile = __DIR__ . '/tpl/Service.tpl';
        }

        // 替换规则
        $replacementRules = [
            '/{{NAMESPACE_PATH}}/'  => $namespacePath,
            '/{{RNT}}/'             => $controlName,
            '/{{CODE_TPL_UPDATE}}/' => $updateCodeString,
            '/{{CODE_TPL_STORE}}/'  => $storeCodeString,
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

        return [];
    }
}
