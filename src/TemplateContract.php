<?php

namespace Mrzkit\LaravelExtensionHierarchical;

interface TemplateContract
{
    /**
     * @return bool
     */
    public function getForceCover() : bool;

    /**
     * @return string
     */
    public function getSaveDirectory() : string;

    /**
     * @return string
     */
    public function getSaveFilename() : string;

    /**
     * @return string
     */
    public function getSourceTemplateFile() : string;

    /**
     * @return string[]
     */
    public function getReplacementRules() : array;

    /**
     * @return string[]
     */
    public function getReplacementRuleCallbacks() : array;
}
