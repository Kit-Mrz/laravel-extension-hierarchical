<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;

class TemplateObject implements TemplateContract
{
    /**
     * @var bool 是否强制覆盖: true=覆盖,false=不覆盖
     */
    private $forceCover;

    /**
     * @var string 保存目录
     */
    private $saveDirectory;

    /**
     * @var string 保存文件名称
     */
    private $saveFilename;

    /**
     * @var string 模板文件
     */
    private $sourceTemplateFile;

    /**
     * @var string[] 替换规则
     */
    private $replacementRules;

    /**
     * @var array 替换规则
     */
    private $replacementRuleCallbacks;

    /**
     * @return bool
     */
    public function getForceCover() : bool
    {
        return $this->forceCover;
    }

    /**
     * @param bool $forceCover
     * @return TemplateObject
     */
    public function setForceCover(bool $forceCover) : TemplateObject
    {
        $this->forceCover = $forceCover;

        return $this;
    }

    /**
     * @return string
     */
    public function getSaveDirectory() : string
    {
        return $this->saveDirectory;
    }

    /**
     * @param string $saveDirectory
     * @return TemplateObject
     */
    public function setSaveDirectory(string $saveDirectory) : TemplateObject
    {
        $this->saveDirectory = $saveDirectory;

        return $this;
    }

    /**
     * @return string
     */
    public function getSaveFilename() : string
    {
        return $this->saveFilename;
    }

    /**
     * @param string $saveFilename
     * @return TemplateObject
     */
    public function setSaveFilename(string $saveFilename) : TemplateObject
    {
        $this->saveFilename = $saveFilename;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceTemplateFile() : string
    {
        return $this->sourceTemplateFile;
    }

    /**
     * @param string $sourceTemplateFile
     * @return TemplateObject
     */
    public function setSourceTemplateFile(string $sourceTemplateFile) : TemplateObject
    {
        $this->sourceTemplateFile = $sourceTemplateFile;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getReplacementRules() : array
    {
        return $this->replacementRules;
    }

    /**
     * @param string[] $replacementRules
     * @return TemplateObject
     */
    public function setReplacementRules(array $replacementRules) : TemplateObject
    {
        $this->replacementRules = $replacementRules;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getReplacementRuleCallbacks() : array
    {
        return $this->replacementRuleCallbacks;
    }

    /**
     * @param array $replacementRuleCallbacks
     * @return TemplateObject
     */
    public function setReplacementRuleCallbacks(array $replacementRuleCallbacks) : TemplateObject
    {
        $this->replacementRuleCallbacks = $replacementRuleCallbacks;

        return $this;
    }
}
