<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateContract;
use Mrzkit\LaravelExtensionHierarchical\Contracts\TemplateHandlerContract;
use Mrzkit\TemplateEngine\TemplateEngine;
use Mrzkit\TemplateEngine\TemplateFileReader;
use Mrzkit\TemplateEngine\TemplateFileWriter;

class TemplateHandler implements TemplateHandlerContract
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

    public function getWriteResult() : bool
    {
        $templateContract = $this->getTemplateContract();
        //
        $reader = new TemplateFileReader($templateContract->getSourceTemplateFile());
        //
        $engine = new TemplateEngine($reader);
        //
        $engine->setContentReplacements($templateContract->getReplacementRules())->setContentReplacementsCallback($templateContract->getReplacementRuleCallbacks());
        //
        $engine->replaceContentReplacements()->replaceContentReplacementsCallback();
        //
        $writer = new TemplateFileWriter($templateContract->getSaveFilename());
        //
        $result = $writer->setContent($engine->getReplaceResult())->setForce($templateContract->getForceCover())->saveFile();

        return $result;
    }

    public function getReplaceResult() : string
    {
        $templateContract = $this->getTemplateContract();
        //
        $reader = new TemplateFileReader($templateContract->getSourceTemplateFile());
        //
        $engine = new TemplateEngine($reader);
        //
        $engine->setContentReplacements($templateContract->getReplacementRules())->setContentReplacementsCallback($templateContract->getReplacementRuleCallbacks());
        //
        $engine->replaceContentReplacements()->replaceContentReplacementsCallback();

        return $engine->getReplaceResult();
    }
}

