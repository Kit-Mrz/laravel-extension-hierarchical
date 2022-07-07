<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
    // 表信息
    private $tableFullColumns;
    // 表字段
    private $tableFields;
    // 表后缀
    private $tableSuffix;

    public function __construct(string $tableName, string $tablePrefix = '')
    {
        $this->tablePrefix      = $tablePrefix;
        $this->tableName        = $tableName;
        $this->tableFullName    = $this->tablePrefix . $this->tableName;
        $this->tableShard       = false;
        $this->tableFullColumns = [];
        $this->tableFields      = [];
        $this->tableSuffix      = "";

        $this->initTable();
        $this->initTableFullColumns();
        $this->initTableFields();
    }

    /**
     * @desc 表初始化
     * @return $this
     */
    public function initTable()
    {
        $this->tablePrefix = strlen($this->tablePrefix) > 0 ? Str::snake($this->tablePrefix) : $this->tablePrefix;

        $tableName = Str::snake($this->getTableName());

        // 猜测表名
        $listSuffix = [
            2   => '_2',
            4   => '_4',
            8   => '_8',
            16  => '_16',
            32  => '_32',
            64  => '_64',
            128 => '_128',
            256 => '_256',
        ];

        if (Schema::hasTable($tableName)) {
            $this->tableName     = Str::snake($tableName);
            $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
            $this->tableShard    = false;
            $this->tableSuffix   = "";
        } else if (Schema::hasTable($tableName . $listSuffix[2])) {
            $this->tableName     = Str::snake($tableName . $listSuffix[2]);
            $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
            $this->tableShard    = true;
            $this->tableSuffix   = $listSuffix[2];
        } else if (Schema::hasTable($tableName . $listSuffix[4])) {
            $this->tableName     = Str::snake($tableName . $listSuffix[4]);
            $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
            $this->tableShard    = true;
            $this->tableSuffix   = $listSuffix[4];
        } else if (Schema::hasTable($tableName . $listSuffix[8])) {
            $this->tableName     = Str::snake($tableName . $listSuffix[8]);
            $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
            $this->tableShard    = true;
            $this->tableSuffix   = $listSuffix[8];
        } else if (Schema::hasTable($tableName . $listSuffix[16])) {
            $this->tableName     = Str::snake($tableName . $listSuffix[16]);
            $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
            $this->tableShard    = true;
            $this->tableSuffix   = $listSuffix[16];
        } else if (Schema::hasTable($tableName . $listSuffix[32])) {
            $this->tableName     = Str::snake($tableName . $listSuffix[32]);
            $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
            $this->tableShard    = true;
            $this->tableSuffix   = $listSuffix[32];
        } else if (Schema::hasTable($tableName . $listSuffix[64])) {
            $this->tableName     = Str::snake($tableName . $listSuffix[64]);
            $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
            $this->tableShard    = true;
            $this->tableSuffix   = $listSuffix[64];
        } else if (Schema::hasTable($tableName . $listSuffix[128])) {
            $this->tableName     = Str::snake($tableName . $listSuffix[128]);
            $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
            $this->tableShard    = true;
            $this->tableSuffix   = $listSuffix[128];
        } else if (Schema::hasTable($tableName . $listSuffix[256])) {
            $this->tableName     = Str::snake($tableName . $listSuffix[256]);
            $this->tableFullName = Str::snake($this->tablePrefix . $this->tableName);
            $this->tableShard    = true;
            $this->tableSuffix   = $listSuffix[256];
        } else {
            throw new \InvalidArgumentException("Not exists table.");
        }

        return $this;
    }

    /**
     * @desc 表信息初始化
     * @return $this
     */
    public function initTableFullColumns()
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
    public function initTableFields()
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
}
