<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Illuminate\Support\Str;

class NewTableParser
{
    private $tableInformationContract;

    public function __construct(TableInformationContract $tableInformationContract)
    {
        $this->tableInformationContract = $tableInformationContract;
    }

    /**
     * @return TableInformationContract
     */
    public function getTableInformationContract() : TableInformationContract
    {
        return $this->tableInformationContract;
    }

    /**
     * @desc 渲染的表名
     * @return string
     */
    public function getRenderTableName() : string
    {
        $tableName = $this->getTableInformationContract()->getTableName();

        $tableName = Str::snake($tableName);

        if ($this->getTableInformationContract()->getTableShard()) {
            $suffix    = $this->getTableInformationContract()->getTableSuffix();
            $tableName = Str::replace($suffix, "", $tableName);
        }

        $tableName = Str::camel($tableName);

        $tableName = ucfirst($tableName);

        return $tableName;
    }

    public function updateCodeTemplate(array $ignoreFields = []) : string
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        $codeString = "";
        foreach ($tableFullColumns as $column) {
            if (in_array($column->Field, $ignoreFields)) {
                continue;
            }

            $snakeField = Str::snake($column->Field);
            $camelField = Str::camel($column->Field);

            //****
            $matcher = new DataTypeMatcher($column->Field, $column->Type, $column->Comment);
            if ( !empty($matcher->matchInt())) {
                $template = "
                    if (isset(\$params[\"%s\"])) {
                        \$data[\"%s\"] = %s (\$params[\"%s\"] ?? %d);
                    }
                ";
                $type     = "(int)";
                $val      = 0;
            } else if ( !empty($matcher->matchFloat())) {
                $template = "
                    if (isset(\$params[\"%s\"])) {
                        \$data[\"%s\"] = %s (\$params[\"%s\"] ?? %2f);
                    }
                ";
                $type     = "(double)";
                $val      = 0.00;
            } else if ( !empty($matcher->matchString())) {
                $template = "
                    if (isset(\$params[\"%s\"])) {
                        \$data[\"%s\"] = %s (\$params[\"%s\"] ?? %s);
                    }
                ";
                $type     = "(string)";
                $val      = "\"\"";
            } else if ( !empty($matcher->matchDate())) {
                $template = "
                    if (isset(\$params[\"%s\"])) {
                        \$data[\"%s\"] = %s (\$params[\"%s\"] ?? %s);
                    }
                ";
                $type     = "";
                $val      = "null";
            } else {
                $template = "
                    if (isset(\$params[\"%s\"])) {
                        \$data[\"%s\"] = %s (\$params[\"%s\"] ?? %s),;
                    }
                ";
                $type     = "(string)";
                $val      = "";
            }
            $codeString .= sprintf($template, $camelField, $snakeField, $type, $camelField, $val, "\r\n");
            //****
        }

        return $codeString;
    }

    /**
     * @desc 代码模板
     * @param array $fields
     * @return string
     */
    public function storeCodeTemplate(array $ignoreFields = []) : string
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        $codeString = "";
        foreach ($tableFullColumns as $column) {
            if (in_array($column->Field, $ignoreFields)) {
                continue;
            }
            $snakeField = Str::snake($column->Field);
            $camelField = Str::camel($column->Field);

            //****
            $matcher = new DataTypeMatcher($column->Field, $column->Type, $column->Comment);
            if ( !empty($matcher->matchInt())) {
                $template = '"%s" => %s ($row["%s"] ?? %d),%s';
                $type     = "(int)";
                $val      = 0;
            } else if ( !empty($matcher->matchFloat())) {
                $template = '"%s" => %s ($row["%s"] ?? %2f),%s';
                $type     = "(double)";
                $val      = 0.00;
            } else if ( !empty($matcher->matchString())) {
                $template = '"%s" => %s ($row["%s"] ?? %s),%s';
                $type     = "(string)";
                $val      = "\"\"";
            } else if ( !empty($matcher->matchDate())) {
                $template = '"%s" => %s ($row["%s"] ?? %s),%s';
                $type     = "";
                $val      = "null";
            } else {
                $template = '"%s" => %s ($row["%s"] ?? %s),%s';
                $type     = "(string)";
                $val      = "";
            }
            $codeString .= sprintf($template, $snakeField, $type, $camelField, $val, "\r\n");
            //****
        }

        return $codeString;
    }

    /**
     * @desc 单元测试模板
     * @param array $fields
     * @return string
     */
    public function unitTestStoreCodeTemplate(array $ignoreFields = []) : string
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        $codeString = "";
        foreach ($tableFullColumns as $column) {
            if (in_array($column->Field, $ignoreFields)) {
                continue;
            }
            $snakeField = Str::snake($column->Field);
            $camelField = Str::camel($column->Field);

            //****
            $matcher = new DataTypeMatcher($column->Field, $column->Type, $column->Comment);
            if ( !empty($matcher->matchInt())) {
                $template = '"%s" => %s %d,%s';
                $type     = "(int)";
                $val      = 0;
            } else if ( !empty($matcher->matchFloat())) {
                $template = '"%s" => %s %2f,%s';
                $type     = "(double)";
                $val      = 0.00;
            } else if ( !empty($matcher->matchString())) {
                $template = '"%s" => %s %s,%s';
                $type     = "(string)";
                $val      = "\"\"";
            } else if ( !empty($matcher->matchDate())) {
                $template = '"%s" => %s %s,%s';
                $type     = "";
                $val      = "null";
            } else {
                $template = '"%s" => %s %s,%s';
                $type     = "(string)";
                $val      = "";
            }
            $codeString .= sprintf($template, $camelField, $type, $val, "\r\n");
            //****
        }

        return $codeString;
    }


    public function getHandleOutputRender(array $ignoreFields = [], bool $camelFirst = true) : string
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        $fieldString = "";

        foreach ($tableFullColumns as $column) {
            if (in_array($column->Field, $ignoreFields)) {
                continue;
            }
            //****
            $matcher = new DataTypeMatcher($column->Field, $column->Type, $column->Comment);
            if ( !empty($matcher->matchInt())) {
                $template = '"%s" => %s $row["%s"],%s';
                $type     = "(int)";
            } else if ( !empty($matcher->matchFloat())) {
                $template = '"%s" => %s $row["%s"],%s';
                $type     = "(double)";
            } else if ( !empty($matcher->matchString())) {
                $template = '"%s" => %s $row["%s"],%s';
                $type     = "(string)";
            } else if ( !empty($matcher->matchDate())) {
                $template = '"%s" => %s $row["%s"],%s';
                $type     = "";
            } else {
                $template = '"%s" => %s $row["%s"],%s';
                $type     = "(string)";
            }
            //****

            $field      = $column->Field;
            $snakeField = Str::snake($field);
            $camelField = Str::camel($field);

            if ($camelFirst) {
                $fieldString .= sprintf($template, $camelField, $type, $snakeField, "\r\n");
            } else {
                $fieldString .= sprintf($template, $snakeField, $type, $camelField, "\r\n");
            }
        }

        return $fieldString;
    }

    public function getHandleOutputRenderBackup(array $ignoreFields = [], bool $camelFirst = true) : string
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        $fieldString = "";

        foreach ($tableFullColumns as $column) {
            if (in_array($column->Field, $ignoreFields)) {
                continue;
            }
            //****
            $matcher = new DataTypeMatcher($column->Field, $column->Type, $column->Comment);
            if ( !empty($matcher->matchInt())) {
                $template = '"%s" => %s ($row["%s"] ?? %d),%s';
                $type     = "(int)";
                $val      = 0;
            } else if ( !empty($matcher->matchFloat())) {
                $template = '"%s" => %s ($row["%s"] ?? %2f),%s';
                $type     = "(double)";
                $val      = 0;
            } else if ( !empty($matcher->matchString())) {
                $template = '"%s" => %s ($row["%s"] ?? %s),%s';
                $type     = "(string)";
                $val      = "\"\"";
            } else if ( !empty($matcher->matchDate())) {
                $template = '"%s" => %s ($row["%s"] ?? %s),%s';
                $type     = "";
                $val      = "null";
            } else {
                $template = '"%s" => %s ($row["%s"] ?? %s),%s';
                $type     = "(string)";
                $val      = "";
            }
            //****

            $field      = $column->Field;
            $snakeField = Str::snake($field);
            $camelField = Str::camel($field);

            if ($camelFirst) {
                $fieldString .= sprintf($template, $camelField, $type, $snakeField, $val, "\r\n");
            } else {
                $fieldString .= sprintf($template, $snakeField, $type, $camelField, $val, "\r\n");
            }
        }

        return $fieldString;
    }

    /**
     * @desc 表字段字符串
     * @return string
     */
    public function getTableFieldString() : string
    {
        $tableFields = $this->getTableInformationContract()->getTableFields();

        return "'" . join("', '", $tableFields) . "',";
    }

    /**
     * @desc 表字段加描述字符串
     * @return string
     */
    public function getFieldInfoString() : string
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        $fieldString = "";

        $template = '["snake" => "%s", "camel" => "%s", "comment" => "%s", ],%s';

        foreach ($tableFullColumns as $item) {
            $field       = $item->Field;
            $comment     = $item->Comment;
            $snakeField  = Str::snake($field);
            $camelField  = Str::camel($field);
            $fieldString .= sprintf($template, $snakeField, $camelField, $comment, "\r\n");
        }

        return $fieldString;
    }

    /**
     * @desc Request.Store 模板
     * @param array $ignoreFields
     * @return RequestTemplateRenderContract
     */
    public function getStoreTemplateRender(array $ignoreFields = [], bool $isBatch = false) : RequestTemplateRenderContract
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        $dataTypeMatchers = collect([]);

        foreach ($tableFullColumns as $column) {
            if (in_array($column->Field, $ignoreFields)) {
                continue;
            }

            $dataTypeMatcher = new DataTypeMatcher($column->Field, $column->Type, $column->Comment);

            $dataTypeMatchers[] = $dataTypeMatcher;
        }

        $ruleString = "";

        $messageString = "";

        $batchTemplate = "";

        if ($isBatch) {
            $batchTemplate = "batch.*.";
        }

        foreach ($dataTypeMatchers as $matcher) {
            $matchResult = $matcher->matchInt();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s"  => "required|integer|between:%d,%d",%s';
                $ruleString .= sprintf($template, $batchTemplate, $field, $matchResult["min"], $matchResult["max"], "\r\n");

                // Message
                $messageString .= "\"{$batchTemplate}{$field}.required\" => \"缺少 {$field} 字段\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.integer\" => \"字段 {$field} 格式错误\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.between\" => \"字段 {$field} 超出范围\",\r\n";

                continue;
            }

            $matchResult = $matcher->matchFloat();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s"  => "required|numeric",%s';
                $ruleString .= sprintf($template, $batchTemplate,$field, "\r\n");

                // Message
                $messageString .= "\"{$batchTemplate}{$field}.required\" => \"缺少 {$field} 字段\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.numeric\" => \"字段 {$field} 格式错误\",\r\n";

                continue;
            }

            $matchResult = $matcher->matchString();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s" => "required|string|nullable|between:%d,%d",%s';
                $ruleString .= sprintf($template, $batchTemplate,$field, $matchResult["min"], $matchResult["max"], "\r\n");
                // Message
                $messageString .= "\"{$batchTemplate}{$field}.required\" => \"缺少 {$field} 字段\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.string\" => \"字段 {$field} 格式错误\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.between\" => \"字段 {$field} 超出范围\",\r\n";

                continue;
            }

            $matchResult = $matcher->matchDate();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s" => "required|date|nullable",%s';
                $ruleString .= sprintf($template, $batchTemplate,$field, "\r\n");
                // Message
                $messageString .= "\"{$batchTemplate}{$field}.required\" => \"缺少 {$field} 字段\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.date\" => \"字段 {$field} 日期格式错误\",\r\n";

                continue;
            }

            // 匹配不到类型
            $field = Str::camel($matcher->getField());
            // Rule
            $template   = '"%s%s" => "string|nullable",%s';
            $ruleString .= sprintf($template, $batchTemplate,$field, "\r\n");
            // Message
            $messageString .= "\"{$batchTemplate}{$field}.string\" => \"字段 {$field} 格式错误\",\r\n";
        }

        $ruleString = "return[\r\n{$ruleString}\r\n];";

        $messageString = "return[\r\n{$messageString}\r\n];";

        $requestTemplateRender = new RequestTemplateRender($ruleString, $messageString);

        return $requestTemplateRender;
    }

    /**
     * @desc Request.Update 模板
     * @param array $ignoreFields
     * @return RequestTemplateRenderContract
     */
    public function getUpdateTemplateRender(array $ignoreFields = [], bool $isBatch = false) : RequestTemplateRenderContract
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        $dataTypeMatchers = collect([]);

        foreach ($tableFullColumns as $column) {
            if (in_array($column->Field, $ignoreFields)) {
                continue;
            }

            $dataTypeMatcher = new DataTypeMatcher($column->Field, $column->Type, $column->Comment);

            $dataTypeMatchers[] = $dataTypeMatcher;
        }

        $ruleString = "";

        $messageString = "";

        $batchTemplate = "";

        if ($isBatch) {
            $batchTemplate = "batch.*.";
        }

        foreach ($dataTypeMatchers as $matcher) {
            $matchResult = $matcher->matchInt();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s"  => "integer|between:%d,%d",%s';
                $ruleString .= sprintf($template, $batchTemplate, $field, $matchResult["min"], $matchResult["max"], "\r\n");

                // Message
                $messageString .= "\"{$batchTemplate}{$field}.integer\" => \"字段 {$field} 格式错误\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.between\" => \"字段 {$field} 超出范围\",\r\n";

                continue;
            }

            $matchResult = $matcher->matchFloat();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s"  => "numeric",%s';
                $ruleString .= sprintf($template, $batchTemplate, $field, "\r\n");

                // Message
                $messageString .= "\"{$batchTemplate}{$field}.numeric\" => \"字段 {$field} 格式错误\",\r\n";

                continue;
            }

            $matchResult = $matcher->matchString();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s" => "string|nullable|between:%d,%d",%s';
                $ruleString .= sprintf($template, $batchTemplate, $field, $matchResult["min"], $matchResult["max"], "\r\n");
                // Message
                $messageString .= "\"{$batchTemplate}{$field}.string\" => \"字段 {$field} 格式错误\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.between\" => \"字段 {$field} 超出范围\",\r\n";

                continue;
            }

            $matchResult = $matcher->matchDate();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s" => "date|nullable",%s';
                $ruleString .= sprintf($template, $batchTemplate, $field, "\r\n");
                // Message
                $messageString .= "\"{$batchTemplate}{$field}.date\" => \"字段 {$field} 日期格式错误\",\r\n";

                continue;
            }

            // 匹配不到类型
            $field = Str::camel($matcher->getField());
            // Rule
            $template   = '"%s%s" => "string|nullable",%s';
            $ruleString .= sprintf($template, $batchTemplate, $field, "\r\n");
            // Message
            $messageString .= "\"{$batchTemplate}{$field}.string\" => \"字段 {$field} 格式错误\",\r\n";
        }

        $ruleString = "return[\r\n{$ruleString}\r\n];";

        $messageString = "return[\r\n{$messageString}\r\n];";

        $requestTemplateRender = new RequestTemplateRender($ruleString, $messageString);

        return $requestTemplateRender;
    }
}
