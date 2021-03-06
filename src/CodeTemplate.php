<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Illuminate\Support\Str;
use Mrzkit\LaravelExtensionHierarchical\Contracts\RequestTemplateRenderContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TableInformationContract;

class CodeTemplate
{
    /**
     * @var TableInformationContract
     */
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

    public function getFillAbleTpl() : string
    {
        $tableFields = $this->getTableInformationContract()->getTableFields();

        return "'" . join("', '", $tableFields) . "',";
    }

    public function getFieldsTpl() : string
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

    public function getShardConfigTpl() : string
    {
        $shardCountConfig = $this->getTableInformationContract()->getShardCountConfig();

        $tpl = "";

        foreach ($shardCountConfig as $item) {
            $tpl .= "
            [
                'partition' => {$item["partition"]},
                'low'       => {$item["low"]},
                'high'      => {$item["high"]},
            ],";
        }

        return $tpl;
    }

    public function getHandleOutputTpl(array $ignoreFields = [], bool $camelFirst = true) : string
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

    public function getServiceUpdateTpl(array $ignoreFields = [], string $itemName = "row", string $paramName = "tempParams") : string
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
                    if (isset(\${$itemName}[\"%s\"])) {
                        \${$paramName}[\"%s\"] = %s (\${$itemName}[\"%s\"] ?? %d);
                    }
                ";
                $type     = "(int)";
                $val      = 0;
            } else if ( !empty($matcher->matchFloat())) {
                $template = "
                    if (isset(\${$itemName}[\"%s\"])) {
                        \${$paramName}[\"%s\"] = %s (\${$itemName}[\"%s\"] ?? %2f);
                    }
                ";
                $type     = "(double)";
                $val      = 0.00;
            } else if ( !empty($matcher->matchString())) {
                $template = "
                    if (isset(\${$itemName}[\"%s\"])) {
                        \${$paramName}[\"%s\"] = %s (\${$itemName}[\"%s\"] ?? %s);
                    }
                ";
                $type     = "(string)";
                $val      = "\"\"";
            } else if ( !empty($matcher->matchDate())) {
                $template = "
                    if (isset(\${$itemName}[\"%s\"])) {
                        \${$paramName}[\"%s\"] = %s (\${$itemName}[\"%s\"] ?? %s);
                    }
                ";
                $type     = "";
                $val      = "null";
            } else {
                $template = "
                    if (isset(\${$itemName}[\"%s\"])) {
                        \${$paramName}[\"%s\"] = %s (\${$itemName}[\"%s\"] ?? %s),;
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

    public function getServiceStoreTpl(array $ignoreFields = [], string $itemName = "row") : string
    {
        $itemName = '$' . $itemName;

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
                $template = '"%s" => %s (%s["%s"] ?? %d),%s';
                $type     = "(int)";
                $val      = 0;
            } else if ( !empty($matcher->matchFloat())) {
                $template = '"%s" => %s (%s["%s"] ?? %2f),%s';
                $type     = "(double)";
                $val      = 0.00;
            } else if ( !empty($matcher->matchString())) {
                $template = '"%s" => %s (%s["%s"] ?? %s),%s';
                $type     = "(string)";
                $val      = "\"\"";
            } else if ( !empty($matcher->matchDate())) {
                $template = '"%s" => %s (%s["%s"] ?? %s),%s';
                $type     = "";
                $val      = "null";
            } else {
                $template = '"%s" => %s (%s["%s"] ?? %s),%s';
                $type     = "(string)";
                $val      = "";
            }
            $codeString .= sprintf($template, $snakeField, $type, $itemName, $camelField, $val, "\r\n");
            //****
        }

        return $codeString;
    }

    public function getRequestStoreTpl(array $ignoreFields = [], bool $isBatch = false) : RequestTemplateRenderContract
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
                $messageString .= "\"{$batchTemplate}{$field}.required\" => \"?????? {$field} ??????\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.integer\" => \"?????? {$field} ????????????\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.between\" => \"?????? {$field} ????????????\",\r\n";

                continue;
            }

            $matchResult = $matcher->matchFloat();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s"  => "required|numeric",%s';
                $ruleString .= sprintf($template, $batchTemplate, $field, "\r\n");

                // Message
                $messageString .= "\"{$batchTemplate}{$field}.required\" => \"?????? {$field} ??????\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.numeric\" => \"?????? {$field} ????????????\",\r\n";

                continue;
            }

            $matchResult = $matcher->matchString();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s" => "required|string|nullable|between:%d,%d",%s';
                $ruleString .= sprintf($template, $batchTemplate, $field, $matchResult["min"], $matchResult["max"], "\r\n");
                // Message
                $messageString .= "\"{$batchTemplate}{$field}.required\" => \"?????? {$field} ??????\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.string\" => \"?????? {$field} ????????????\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.between\" => \"?????? {$field} ????????????\",\r\n";

                continue;
            }

            $matchResult = $matcher->matchDate();
            if ( !empty($matchResult)) {
                //
                $field = Str::camel($matcher->getField());
                // Rule
                $template   = '"%s%s" => "required|date|nullable",%s';
                $ruleString .= sprintf($template, $batchTemplate, $field, "\r\n");
                // Message
                $messageString .= "\"{$batchTemplate}{$field}.required\" => \"?????? {$field} ??????\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.date\" => \"?????? {$field} ??????????????????\",\r\n";

                continue;
            }

            // ??????????????????
            $field = Str::camel($matcher->getField());
            // Rule
            $template   = '"%s%s" => "string|nullable",%s';
            $ruleString .= sprintf($template, $batchTemplate, $field, "\r\n");
            // Message
            $messageString .= "\"{$batchTemplate}{$field}.string\" => \"?????? {$field} ????????????\",\r\n";
        }

        $requestTemplateRender = new RequestTemplateRender($ruleString, $messageString);

        return $requestTemplateRender;
    }

    public function getRequestUpdateTpl(array $ignoreFields = [], bool $isBatch = false) : RequestTemplateRenderContract
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
                $messageString .= "\"{$batchTemplate}{$field}.integer\" => \"?????? {$field} ????????????\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.between\" => \"?????? {$field} ????????????\",\r\n";

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
                $messageString .= "\"{$batchTemplate}{$field}.numeric\" => \"?????? {$field} ????????????\",\r\n";

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
                $messageString .= "\"{$batchTemplate}{$field}.string\" => \"?????? {$field} ????????????\",\r\n";
                $messageString .= "\"{$batchTemplate}{$field}.between\" => \"?????? {$field} ????????????\",\r\n";

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
                $messageString .= "\"{$batchTemplate}{$field}.date\" => \"?????? {$field} ??????????????????\",\r\n";

                continue;
            }

            // ??????????????????
            $field = Str::camel($matcher->getField());
            // Rule
            $template   = '"%s%s" => "string|nullable",%s';
            $ruleString .= sprintf($template, $batchTemplate, $field, "\r\n");
            // Message
            $messageString .= "\"{$batchTemplate}{$field}.string\" => \"?????? {$field} ????????????\",\r\n";
        }

        $requestTemplateRender = new RequestTemplateRender($ruleString, $messageString);

        return $requestTemplateRender;
    }

    public function getUnitTestStoreTpl(array $ignoreFields = []) : string
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        /** @var \Faker\Generator::class $faker */
        $faker = app(\Faker\Generator::class);

        $codeString = "";
        foreach ($tableFullColumns as $column) {
            if (in_array($column->Field, $ignoreFields)) {
                continue;
            }
            $camelField = Str::camel($column->Field);

            //****
            $matcher = new DataTypeMatcher($column->Field, $column->Type, $column->Comment);
            if ( !empty($matchResult = $matcher->matchInt())) {
                $template = '"%s" => %s %d,%s';
                $type     = "(int)";
                $val      = mt_rand(0, $matchResult["max"] ?? 2147483647);
            } else if ( !empty($matcher->matchFloat())) {
                $template = '"%s" => %s %2f,%s';
                $type     = "(double)";
                $val      = mt_rand(0, $matchResult["max"] ?? 2147483647);;
            } else if ( !empty($matcher->matchString())) {
                $text     = $faker->realTextBetween(5, 100);
                $text     = str_replace('"', '', $text);
                $text     = addslashes($text);
                $template = '"%s" => %s %s,%s';
                $type     = "(string)";
                $val      = "\"{$text}\"";
            } else if ( !empty($matcher->matchDate())) {
                $template = '"%s" => %s %s,%s';
                $type     = "";
                $val      = "date('Y-m-d H:i:s')";
            } else {
                $text     = $faker->realTextBetween(5, 100);
                $text     = str_replace('"', '', $text);
                $text     = addslashes($text);
                $template = '"%s" => %s %s,%s';
                $type     = "(string)";
                $val      = "{$text}";
            }
            $codeString .= sprintf($template, $camelField, $type, $val, "\r\n");
            //****
        }

        return $codeString;
    }

    public function getUnitTestStoreSeedTpl(array $ignoreFields = []) : string
    {
        $tableFullColumns = $this->getTableInformationContract()->getTableFullColumns();

        $codeString = "";
        foreach ($tableFullColumns as $column) {
            if (in_array($column->Field, $ignoreFields)) {
                continue;
            }
            $camelField = Str::camel($column->Field);

            //****
            $matcher = new DataTypeMatcher($column->Field, $column->Type, $column->Comment);
            if ( !empty($matchResult = $matcher->matchInt())) {
                $max      = $matchResult["max"] ?? 2147483647;
                $template = '"%s" => %s %s,%s';
                $type     = "(int)";
                $val      = "mt_rand(0, {$max})";
            } else if ( !empty($matchResult = $matcher->matchFloat())) {
                $max      = $matchResult["max"] ?? 2147483647;
                $template = '"%s" => %s %s,%s';
                $type     = "(double)";
                $val      = "mt_rand(0, {$max})";
            } else if ( !empty($matchResult = $matcher->matchString())) {
                $max      = $matchResult["max"] ?? 100;
                $template = '"%s" => %s %s,%s';
                $type     = "(string)";
                $val      = "addslashes(\$this->getFaker()->realTextBetween(5, {$max}))";
            } else if ( !empty($matchResult = $matcher->matchDate())) {
                $template = '"%s" => %s %s,%s';
                $type     = "";
                $val      = "date('Y-m-d H:i:s')";
            } else {
                $template = '"%s" => %s %s,%s';
                $type     = "(string)";
                $val      = "addslashes(\$this->getFaker()->realTextBetween(5, 100))";
            }
            $codeString .= sprintf($template, $camelField, $type, $val, "\r\n");
            //****
        }

        return $codeString;
    }
}

