<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Mrzkit\TemplateEngine\TemplateEngine;
use Mrzkit\TemplateEngine\TemplateFileReader;
use Mrzkit\TemplateEngine\TemplateFileWriter;

class TemplateHandler
{
    /**
     * @var TemplateContract
     */
    private $templateContract;

    /**
     * @return TemplateContract
     */
    public function getTemplateContract() : TemplateContract
    {
        return $this->templateContract;
    }

    /**
     * @param TemplateContract $templateContract
     */
    public function setTemplateContract(TemplateContract $templateContract) : TemplateHandler
    {
        $this->templateContract = $templateContract;

        return $this;
    }

    public function execute() : bool
    {
        return $this->handle($this->getTemplateContract());
    }

    public function handle(TemplateContract $templateContract) : bool
    {
        // 执行
        $templateContract->handle();
        // 读取文件
        $reader = new TemplateFileReader($templateContract->getSourceTemplateFile());
        // 实例化替换引擎
        $engine = new TemplateEngine($reader);
        // 初始化配置
        $engine->setContentReplacements($templateContract->getReplacementRules())->setContentReplacementsCallback($templateContract->getReplacementRuleCallbacks());
        // 执行替换
        $engine->replaceContentReplacements()->replaceContentReplacementsCallback();
        // 写入文件
        $writer = new TemplateFileWriter($templateContract->getSaveFilename());
        // 写入操作
        $result = $writer->setContent($engine->getReplaceResult())->setForce($templateContract->getForceCover())->saveFile();

        return $result;
    }

    public function executeReplace() : string
    {
        return $this->handleReplace($this->getTemplateContract());
    }

    /**
     * @desc 不写入，仅替换
     * @param TemplateContract $templateContract
     * @return string
     */
    public function handleReplace(TemplateContract $templateContract) : string
    {
        // 执行
        $templateContract->handle();
        // 读取文件
        $reader = new TemplateFileReader($templateContract->getSourceTemplateFile());
        // 实例化替换引擎
        $engine = new TemplateEngine($reader);
        // 初始化配置
        $engine->setContentReplacements($templateContract->getReplacementRules())->setContentReplacementsCallback($templateContract->getReplacementRuleCallbacks());
        // 执行替换
        $engine->replaceContentReplacements()->replaceContentReplacementsCallback();

        // 返回替换结果
        return $engine->getReplaceResult();
    }
}

