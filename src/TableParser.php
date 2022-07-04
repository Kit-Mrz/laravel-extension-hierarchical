<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use InvalidArgumentException;

class TableParser
{
    /**
     * @var string 表名
     */
    protected $tableName = '';

    /**
     * @var string 表前缀
     */
    protected $prefix = '';

    /**
     * @var string 全表名
     */
    protected $fullTableName = '';

    /**
     * @var array 字段
     */
    protected $fields = [];
    /**
     * @var array 全列详细信息
     */
    protected $fullColumns = [];

    public function __construct(string $tableName, string $prefix = '')
    {
        $this->setTableName(Str::snake($tableName));

        $this->prefix = $prefix;

        $this->hasTable();

        $this->fullTableName = $this->getPrefix() . $this->getTableName();

        $this->setTableName(Str::snake($tableName));

        $this->setFullColumns();

        $this->setFields();
    }

    /**
     * @desc 检测表是否存在
     * @return bool
     */
    public function hasTable() : bool
    {
        $tableName = $this->getTableName();

        $tableName16 = $tableName . '_16';

        $tableName32 = $tableName . '_32';

        switch (true) {
            case Schema::hasTable($tableName):
                $this->setTableName($tableName);

                return true;
            case Schema::hasTable($tableName16):
                $this->setTableName($tableName16);

                return true;
            case Schema::hasTable($tableName32):
                $this->setTableName($tableName32);

                return true;
            default:
                throw new InvalidArgumentException("Not exists table: {$tableName}, {$tableName16}, {$tableName32}.");
        }
    }

    /**
     * @desc 表名
     * @return string
     */
    public function getTableName() : string
    {
        return $this->tableName;
    }

    /**
     * @desc
     * @param string $tableName
     * @return $this
     */
    public function setTableName(string $tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @desc 表前缀
     * @return string
     */
    public function getPrefix() : string
    {
        return $this->prefix;
    }

    /**
     * @desc 全表名
     * @return string
     */
    public function getFullTableName() : string
    {
        return $this->fullTableName;
    }

    /**
     * @desc 将字段生成下划线和驼峰
     * @return array
     */
    public function getUnionFields() : array
    {
        $fields = [];

        foreach ($this->getFields() as $item) {
            $fields[$item]['under'] = $item;
            $fields[$item]['hump']  = Str::camel($item);
        }

        return $fields;
    }

    /**
     * @desc  获取字符(数组)
     * @return array
     */
    public function getFields() : array
    {
        return $this->fields;
    }

    /**
     * @desc 提取字段
     * @return $this
     */
    public function setFields()
    {
        $fields = [];

        foreach ($this->getFullColumns() as $item) {
            $fields[] = $item->Field;
        }

        $this->fields = $fields;

        return $this;
    }

    public function getFieldComment() : string
    {
        $str = '';
        foreach ($this->getFullColumns() as $item) {
            $field   = $item->Field;
            $comment = $item->Comment;

            $hump = Str::camel($field);
            $str  .= "\"{$hump}\" => \"{$comment}\",\r\n";
        }

        return $str;
    }

    /**
     * @desc 表信息
     * @return mixed
     */
    public function getFullColumns() : array
    {
        return $this->fullColumns;
    }

    /**
     * @desc 查询表信息
     * @return $this
     */
    public function setFullColumns()
    {
        $tableName = $this->getFullTableName();

        $sql = "SHOW FULL COLUMNS FROM {$tableName}";

        // 原生 SQL 要带上表前缀
        $resultSets = DB::select($sql);

        $ignoreFields = $this->ignoreFields();

        $fullColumns = [];

        foreach ($resultSets as $item) {
            if (in_array($item->Field, $ignoreFields)) {
                continue;
            }

            $fullColumns[] = $item;
        }

        $this->fullColumns = $fullColumns;

        return $this;
    }

    // 获取字段的数组字符串，带表字段注释

    /**
     * @desc 获取字符(字符串)
     * @return string
     */
    public function getFieldString()
    {
        $fields = $this->getFields();

        return "'" . join("', '", $fields) . "',";
    }

    /**
     * @desc 获取字符(字符串)
     * @return string
     */
    public function getHumpFieldsString()
    {
        $fields = $this->getHumpFields();

        return "'" . join("', '", $fields) . "',";
    }

    /**
     * @desc 获取驼峰格式 base_user -> baseUser
     * @return array
     */
    public function getHumpFields() : array
    {
        $fields = [];

        foreach ($this->getFields() as $item) {
            $fields[] = Str::camel($item);
        }

        return $fields;
    }

    /**
     * @desc 获取忽略字段
     * @return string[]
     */
    public function ignoreFields() : array
    {
        $ignoreFields = [
            //'id',
            //'created_by', 'updated_by', 'deleted_by',
            //'created_at', 'updated_at', 'deleted_at',
            //'deleted_by', 'deleted_at',
        ];

        return $ignoreFields;
    }

    public function getRules()
    {
        $listFields = $this->matchType();

        return $this->replaceRules($listFields);
    }

    /**
     * @desc 匹配类型
     * @return array
     */
    public function matchType()
    {
        $rs = [];

        foreach ($this->getFullColumns() as $item) {
            $temp = [
                'field'     => $item->Field,
                '_type'     => $item->Type,
                'comment'   => $item->Comment,
                'humpField' => Str::camel($item->Field),
                'unsigned'  => false,
                'length'    => 0,
            ];

            if (preg_match('/^int.*(unsigned)?/', $item->Type, $matches)) {
                $temp['type'] = 'int';
                if (preg_match('/unsigned/', $matches[0])) {
                    $temp['unsigned'] = true;
                } else {
                    $temp['unsigned'] = false;
                }
            } else if (preg_match('/^tinyint.*(unsigned)?/', $item->Type, $matches)) {
                $temp['type'] = 'tinyint';
                if (preg_match('/unsigned/', $matches[0])) {
                    $temp['unsigned'] = true;
                } else {
                    $temp['unsigned'] = false;
                }
            } else if (preg_match('/^bigint.*(unsigned)?/', $item->Type, $matches)) {
                $temp['type'] = 'bigint';
                if (preg_match('/unsigned/', $matches[0])) {
                    $temp['unsigned'] = true;
                } else {
                    $temp['unsigned'] = false;
                }
            } else if (preg_match('/^float.*(unsigned)?/', $item->Type, $matches)) {
                $temp['type'] = 'float';
                if (preg_match('/unsigned/', $matches[0])) {
                    $temp['unsigned'] = true;
                } else {
                    $temp['unsigned'] = false;
                }
            } else if (preg_match('/^double.*(unsigned)?/', $item->Type, $matches)) {
                $temp['type'] = 'double';
                if (preg_match('/unsigned/', $matches[0])) {
                    $temp['unsigned'] = true;
                } else {
                    $temp['unsigned'] = false;
                }
            } else if (preg_match('/^decimal.*(unsigned)?/', $item->Type, $matches)) {
                $temp['type'] = 'decimal';
                if (preg_match('/unsigned/', $matches[0])) {
                    $temp['unsigned'] = true;
                } else {
                    $temp['unsigned'] = false;
                }
            } else if (preg_match('/^varchar\((\d+)\)/', $item->Type, $matches)) {
                $temp['type']   = 'varchar';
                $temp['length'] = $matches[1];
            } else if (preg_match('/^char\((\d+)\)/', $item->Type, $matches)) {
                $temp['type']   = 'char';
                $temp['length'] = $matches[1];
            } else if (preg_match('/^datetime/', $item->Type, $matches)) {
                $temp['type'] = 'datetime';
            } else if (preg_match('/^timestamp/', $item->Type, $matches)) {
                $temp['type'] = 'timestamp';
            } else {
                $temp['type'] = 'none';
            }

            $rs[] = $temp;
        }

        return $rs;
    }

    /**
     * @desc 替换规则
     * @param array $listFields
     * @return string
     */
    protected function replaceRules(array $listFields)
    {
        $str = '';
        foreach ($listFields as $fieldItem) {
            switch ($fieldItem['type']) {
                case 'tinyint':
                    $min = $fieldItem['unsigned'] ? 0 : -127;
                    $max = $fieldItem['unsigned'] ? 255 : 128;
                    $str .= "\"{$fieldItem['humpField']}\" => \"required|integer|between:{$min},{$max}\",\r\n";
                    break;
                case 'int':
                case 'bigint':
                    $max = 4294967295;
                    $str .= "\"{$fieldItem['humpField']}\" => \"required|integer|between:0,{$max}\",\r\n";
                    break;
                case 'char':
                case 'varchar':
                    $max = $fieldItem['length'];
                    $str .= "\"{$fieldItem['humpField']}\" => \"required|string|between:0,{$max}\",\r\n";
                    break;
                case 'float':
                case 'double':
                case 'decimal':
                    $max = 4294967295;
                    $str .= "\"{$fieldItem['humpField']}\" => \"required|numeric|between:0,{$max}\",\r\n";
                    break;
                case 'datetime':
                case 'timestamp':
                    $str .= "\"{$fieldItem['humpField']}\" => \"required|date\",\r\n";
                    break;
                default:
                    break;
            }
        }

        $ruleString = "return [%s];";

        $result = sprintf($ruleString, $str);

        return $result;
    }

    public function getMessages()
    {
        $listFields = $this->matchType();

        return $this->replaceMessages($listFields);
    }

    /**
     * @desc 替换信息
     * @param array $listFields
     * @return string
     */
    protected function replaceMessages(array $listFields)
    {
        $str = '';
        foreach ($listFields as $fieldItem) {
            switch ($fieldItem['type']) {
                case 'tinyint':
                case 'int':
                case 'bigint':
                    $str .= "\"{$fieldItem['humpField']}.required\" => \"缺少 {$fieldItem['humpField']} 字段\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.integer\" => \"字段 {$fieldItem['humpField']} 格式错误\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.between\" => \"字段 {$fieldItem['humpField']} 超出长度\",\r\n\r\n";
                    break;
                case 'char':
                case 'varchar':
                    $str .= "\"{$fieldItem['humpField']}.required\" => \"缺少 {$fieldItem['humpField']} 字段\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.regex\" => \"字段 {$fieldItem['humpField']} 格式错误，仅允许输入中文、英文、数字、下划线(_)、连接符(-)\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.between\" => \"字段 {$fieldItem['humpField']} 超出长度，允许 0~{$fieldItem['length']} 个字符\",\r\n\r\n";
                    break;
                case 'float':
                case 'double':
                case 'decimal':
                    $str .= "\"{$fieldItem['humpField']}.required\" => \"缺少 {$fieldItem['humpField']} 字段\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.numeric\" => \"字段 {$fieldItem['humpField']} 格式错误\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.between\" => \"字段 {$fieldItem['humpField']} 超出长度\",\r\n\r\n";
                    break;
                case 'datetime':
                case 'timestamp':
                    $str .= "\"{$fieldItem['humpField']}.required\" => \"缺少 {$fieldItem['humpField']} 字段\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.date\" => \"字段 {$fieldItem['humpField']} 格式错误\",\r\n\r\n";
                    break;
                default:
                    break;
            }
        }

        $messageString = "return [%s];";

        $result = sprintf($messageString, $str);

        return $result;
    }

    public function getSimpleRules()
    {
        $listFields = $this->matchType();

        return $this->replaceSimpleRules($listFields);
    }

    /**
     * @desc 替换规则
     * @param array $listFields
     * @return string
     */
    protected function replaceSimpleRules(array $listFields)
    {
        $str = '';
        foreach ($listFields as $fieldItem) {
            switch ($fieldItem['type']) {
                case 'tinyint':
                    $min = $fieldItem['unsigned'] ? 0 : -127;
                    $max = $fieldItem['unsigned'] ? 255 : 128;
                    $str .= "\"{$fieldItem['humpField']}\" => \"integer|between:{$min},{$max}\",\r\n";
                    break;
                case 'int':
                case 'bigint':
                    $max = 4294967295;
                    $str .= "\"{$fieldItem['humpField']}\" => \"integer|between:0,{$max}\",\r\n";
                    break;
                case 'char':
                case 'varchar':
                    $max = $fieldItem['length'];
                    $str .= "\"{$fieldItem['humpField']}\" =>  \"string|between:0,{$max}\",\r\n";
                    break;
                case 'float':
                case 'double':
                case 'decimal':
                    $max = 4294967295;
                    $str .= "\"{$fieldItem['humpField']}\" => \"numeric|between:0,{$max}\",\r\n";
                    break;
                case 'datetime':
                case 'timestamp':
                    $str .= "\"{$fieldItem['humpField']}\" => \"date\",\r\n";
                    break;
                default:
                    break;
            }
        }

        $ruleString = "return [%s];";

        $result = sprintf($ruleString, $str);

        return $result;
    }

    public function getSimpleMessages()
    {
        $listFields = $this->matchType();

        return $this->replaceSimpleMessages($listFields);
    }

    /**
     * @desc 替换信息
     * @param array $listFields
     * @return string
     */
    protected function replaceSimpleMessages(array $listFields)
    {
        $str = '';
        foreach ($listFields as $fieldItem) {
            switch ($fieldItem['type']) {
                case 'tinyint':
                case 'int':
                case 'bigint':
                    $str .= "\"{$fieldItem['humpField']}.integer\" => \"字段 {$fieldItem['humpField']} 格式错误\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.between\" => \"字段 {$fieldItem['humpField']} 超出长度\",\r\n\r\n";
                    break;
                case 'char':
                case 'varchar':
                    $str .= "\"{$fieldItem['humpField']}.regex\" => \"字段 {$fieldItem['humpField']} 格式错误，仅允许输入中文、英文、数字、下划线(_)、连接符(-)\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.between\" => \"字段 {$fieldItem['humpField']} 超出长度，允许 0~{$fieldItem['length']} 个字符\",\r\n\r\n";
                    break;
                case 'float':
                case 'double':
                case 'decimal':
                    $str .= "\"{$fieldItem['humpField']}.numeric\" => \"字段 {$fieldItem['humpField']} 格式错误\",\r\n";
                    $str .= "\"{$fieldItem['humpField']}.between\" => \"字段 {$fieldItem['humpField']} 超出长度\",\r\n\r\n";
                    break;
                case 'datetime':
                case 'timestamp':
                    $str .= "\"{$fieldItem['humpField']}.date\" => \"字段 {$fieldItem['humpField']} 格式错误\",\r\n\r\n";
                    break;
                default:
                    break;
            }
        }

        $messageString = "return [%s];";

        $result = sprintf($messageString, $str);

        return $result;
    }
}
