<?php

namespace Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates;

use Mrzkit\LaravelExtensionHierarchical\NewTableParser;
use Mrzkit\LaravelExtensionHierarchical\TableInformation;
use Mrzkit\LaravelExtensionHierarchical\TemplateAbstract;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\TemplateCreatorContract;

class ModelRepository extends TemplateAbstract implements TemplateCreatorContract
{
    /**
     * @var string 数据表名称
     */
    private $tableName;

    /**
     * @var string 数据表前缀
     */
    private $tablePrefix;

    /**
     * @var bool
     */
    private $shard;

    /**
     * @var NewTableParser 数据表解析器
     */
    private $tableParser;

    public function __construct(string $tableName, string $tablePrefix = '', bool $shard = false)
    {
        $this->tableName   = $tableName;
        $this->tablePrefix = $tablePrefix;
        $this->shard       = $shard;
        $this->tableParser = new NewTableParser(new TableInformation($tableName, $tablePrefix));
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
            "deletedBy", "deletedAt",
            "deleted_by", "deleted_at",
        ];
    }

    public function handle() : array
    {
        $parser = $this->getTableParser();

        $tableName = $parser->getRenderTableName();

        $handleOutputString         = $parser->getHandleOutputRender($this->getIgnoreFields());
        $handleOutputRelationString = $parser->getHandleOutputRender($this->getIgnoreFields(), false);

        //********************************************************

        // 是否强制覆盖: true=覆盖,false=不覆盖
        $forceCover = false;

        // 保存目录
        $saveDirectory = app()->basePath("app/Repositories/{$tableName}");

        // 保存文件名称
        $saveFilename = $saveDirectory . '/' . $tableName . 'Repository.php';

        // 模板文件
        $sourceTemplateFile = __DIR__ . '/tpl/ModelRepository.tpl';

        // 替换规则
        $replacementRules = [
            '/{{RNT}}/'                => $tableName,
            '/{{CODE_TPL_ITEM}}/'      => $handleOutputString,
            '/{{CODE_TPL_ITEM_HUMP}}/' => $handleOutputRelationString,
        ];

        // 替换规则-回调
        $replacementRuleCallbacks = [
            '/(\->)(\\$)(\w+)/' => function ($match){
                $symbol    = $match[1];
                $tableName = $match[3];
                $humpName  = strtolower(substr($tableName, 0, 2)) . substr($tableName, 2);

                return $symbol . $humpName;
            },
            '/(\\$)(\w+)/'      => function ($match){
                $symbolDollar = $match[1];
                $tableName    = $match[2];
                $humpName     = strtolower(substr($tableName, 0, 1)) . substr($tableName, 1);

                return $symbolDollar . $humpName;
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
