<?php

namespace Mrzkit\LaravelExtensionHierarchical;

abstract class TemplateAbstract implements TemplateContract
{
    /**
     * @var bool 是否强制覆盖: true=覆盖,false=不覆盖
     */
    protected $forceCover;

    /**
     * @var string 保存目录
     */
    protected $saveDirectory;

    /**
     * @var string 保存文件名称
     */
    protected $saveFilename;

    /**
     * @var string 模板文件
     */
    protected $sourceTemplateFile;

    /**
     * @var string[] 替换规则
     */
    protected $replacementRules;

    /**
     * @var array 替换规则
     */
    protected $replacementRuleCallbacks;

    /**
     * @return bool
     */
    public function getForceCover() : bool
    {
        return $this->forceCover;
    }

    /**
     * @param bool $forceCover
     * @return TemplateAbstract
     */
    public function setForceCover(bool $forceCover) : TemplateAbstract
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
     * @return TemplateAbstract
     */
    public function setSaveDirectory(string $saveDirectory) : TemplateAbstract
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
     * @return TemplateAbstract
     */
    public function setSaveFilename(string $saveFilename) : TemplateAbstract
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
     * @return TemplateAbstract
     */
    public function setSourceTemplateFile(string $sourceTemplateFile) : TemplateAbstract
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
     * @return TemplateAbstract
     */
    public function setReplacementRules(array $replacementRules) : TemplateAbstract
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
     * @return TemplateAbstract
     */
    public function setReplacementRuleCallbacks(array $replacementRuleCallbacks) : TemplateAbstract
    {
        $this->replacementRuleCallbacks = $replacementRuleCallbacks;

        return $this;
    }
}
