<?php

namespace Mrzkit\LaravelExtensionHierarchical;

trait TemplateTool
{
    public static function validateControlName(string $controlName) : bool
    {
        $pattern = "/^[A-Za-z](\w+)?(\.?\w+)?/";

        if (preg_match($pattern, $controlName)) {
            return true;
        }

        return false;
    }

    public static function processFirstControlName(string $controlName) : string
    {
        $numericalPosition = strpos($controlName, '.');

        if ($numericalPosition !== false) {
            $firstControlName = substr($controlName, 0, $numericalPosition);
        } else {
            $firstControlName = $controlName;
        }

        return $firstControlName;
    }

    public static function processControlName(string $controlName) : string
    {
        $numericalPosition = strripos($controlName, '.');

        if ($numericalPosition !== false) {
            $controlName = substr($controlName, $numericalPosition + 1);
        }

        return $controlName;
    }

    public static function processNamespacePath(string $controlName) : string
    {
        $numericalPosition = strripos($controlName, '.');

        if ($numericalPosition !== false) {
            $namespacePath = substr($controlName, 0, $numericalPosition);
            $namespacePath = str_replace('.', '\\', $namespacePath);
        } else {
            $namespacePath = $controlName;
        }

        return $namespacePath;
    }

    public static function processDirectoryPath(string $controlName) : string
    {
        $namespacePath = static::processNamespacePath($controlName);

        $directoryPath = str_replace('\\', DIRECTORY_SEPARATOR, $namespacePath);

        $directoryPath = strlen($directoryPath) > 0 ? $directoryPath . DIRECTORY_SEPARATOR : $directoryPath;

        return $directoryPath;
    }
}
