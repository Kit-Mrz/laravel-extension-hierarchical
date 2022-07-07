<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Illuminate\Support\Str;

trait TemplateTool
{
    public function validateControlName(string $controlName) : bool
    {
        $pattern = "/^[A-Za-z](\w+)?(\.?\w+)?/";

        if (preg_match($pattern, $controlName)) {
            return true;
        }

        return false;
    }

    public function convertRenderTableName(string $tableName) : string
    {
        if ( !Str::contains('_', $tableName)) {
            $tableName = Str::snake($tableName);
        }

        $tableName = Str::camel($tableName);

        $tableName = ucfirst($tableName);

        return $tableName;
    }
}
