<?php

namespace Mrzkit\LaravelExtensionHierarchical;

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
}
