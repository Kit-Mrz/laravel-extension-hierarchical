<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;

class TableInformation implements TableInformationContract
{
    // 表前缀
    private $tablePrefix;
    // 表名
    private $tableName;
    // 表全名
    private $tableFullName;
    // 分表
    private $tableShard;
    // 期望分表数
    private $shardCount;
    // 最大分表数
    private $maxShardCount;
    // 表信息
    private $tableFullColumns;
    // 表字段
    private $tableFields;
    // 表后缀
    private $tableSuffix;

    public function __construct(string $tableName, string $tablePrefix = '', bool $tableShard = false, int $shardCount = 2, int $maxShardCount = 64)
    {
        $this->tableName        = $tableName;
        $this->tablePrefix      = $tablePrefix;
        $this->tableFullName    = $this->tablePrefix . $this->tableName;
        $this->tableShard       = $tableShard;
        $this->shardCount       = $shardCount;
        $this->maxShardCount    = $maxShardCount;
        $this->tableFullColumns = [];
        $this->tableFields      = [];
        $this->tableSuffix      = "";

        $this->initTable();
        $this->initTableFullColumns();
        $this->initTableFields();
    }

    private function initNormalTable()
    {
        $this->tablePrefix = strlen($this->getTablePrefix()) > 0 ? Str::snake($this->getTablePrefix()) : $this->getTablePrefix();

        $tableName = Str::snake($this->getTableName());

        if ( !Schema::hasTable($tableName)) {
            throw new \InvalidArgumentException("Not exists Normal table: {$tableName}.");
        }

        $this->tableName     = Str::snake($tableName);
        $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
        $this->tableShard    = false;
        $this->tableSuffix   = "";
    }

    private function initShardTable()
    {
        $this->tablePrefix = strlen($this->getTablePrefix()) > 0 ? Str::snake($this->getTablePrefix()) : $this->getTablePrefix();

        if ($this->getShardCount() < 2 || $this->getShardCount() > $this->getMaxShardCount()) {
            throw new \Exception("分表数必须是2~64");
        }

        if ( !$this->isPower($this->getShardCount())) {
            throw new \Exception("分表数必须是2次方数");
        }

        $listSuffix = $this->getSuffixCount();

        if (empty($listSuffix)) {
            throw new \Exception("分表后缀为空");
        }

        $shardTableName = "";

        $tableSuffix = "";

        foreach ($listSuffix as $itemSuffix) {
            $shardTableName = $this->getTableName() . '_' . $itemSuffix;

            if ( !Schema::hasTable($shardTableName)) {
                throw new \InvalidArgumentException("Not exists Shard table: {$shardTableName}.");
            }

            $tableSuffix = $itemSuffix;
        }

        $this->tableName     = Str::snake($shardTableName);
        $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
        $this->tableShard    = true;
        $this->tableSuffix   = $tableSuffix;
    }

    /**
     * @desc 表初始化
     * @return $this
     */
    private function initTable()
    {
        if ($this->getTableShard()) {
            $this->initShardTable();
        } else {
            $this->initNormalTable();
        }

        return $this;
    }

    /**
     * @desc 表信息初始化
     * @return $this
     */
    private function initTableFullColumns()
    {
        $tableFullName = $this->getTableFullName();

        $sql = "SHOW FULL COLUMNS FROM `{$tableFullName}`";

        $resultSets = DB::select($sql);

        $tableFullColumns = [];

        foreach ($resultSets as $item) {
            $tableFullColumns[] = $item;
        }

        $this->tableFullColumns = $tableFullColumns;

        return $this;
    }

    /**
     * @desc 表字段初始化
     * @return $this
     */
    private function initTableFields()
    {
        $tableFields = [];

        foreach ($this->getTableFullColumns() as $item) {
            $tableFields[] = $item->Field;
        }

        $this->tableFields = $tableFields;

        return $this;
    }

    /**
     * @return string
     */
    public function getTablePrefix() : string
    {
        return $this->tablePrefix;
    }

    /**
     * @return string
     */
    public function getTableName() : string
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getTableFullName() : string
    {
        return $this->tableFullName;
    }

    /**
     * @return false
     */
    public function getTableShard() : bool
    {
        return $this->tableShard;
    }

    /**
     * @return int
     */
    public function getShardCount() : int
    {
        return $this->shardCount;
    }

    /**
     * @return array
     */
    public function getTableFullColumns() : array
    {
        return $this->tableFullColumns;
    }

    /**
     * @return array
     */
    public function getTableFields() : array
    {
        return $this->tableFields;
    }

    /**
     * @return string
     */
    public function getTableSuffix() : string
    {
        return $this->tableSuffix;
    }

    /**
     * @return int
     */
    public function getMaxShardCount() : int
    {
        return $this->maxShardCount;
    }

    /**
     * @desc
     * @param int $n
     * @return bool
     */
    public function isPower(int $n) : bool
    {
        if ($n < 2) {
            return false;
        }

        if (($n & ($n - 1)) == 0) {
            return true;
        }

        return false;
    }

    /**
     * @desc
     * @return array
     */
    public function getSuffixCount() : array
    {
        $maxShardCount = $this->getMaxShardCount();

        $shardCount = $this->getShardCount();

        $suffixConfig = [];

        for ($i = 0; $i < $shardCount; $i++) {
            $part = $maxShardCount / $shardCount;

            $suffixConfig[] = ($i + 1) * $part;
        }

        return $suffixConfig;
    }

    /**
     * @desc
     * @return array
     */
    public function getShardCountConfig() : array
    {
        $maxShardCount = $this->getMaxShardCount();
        $shardCount    = $this->getShardCount();

        $shardCountConfig = [];

        for ($i = 0; $i < $shardCount; $i++) {
            $part = $maxShardCount / $shardCount;

            $shardCountConfig[] = [
                'partition' => ($i + 1) * $part,
                'low'       => $i * $part,
                'high'      => ($i + 1) * $part - 1,
            ];
        }

        return $shardCountConfig;
    }
}
