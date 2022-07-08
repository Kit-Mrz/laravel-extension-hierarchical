<?php

namespace Mrzkit\LaravelExtensionHierarchical;

class RequestTemplateRender implements RequestTemplateRenderContract
{
    /**
     * @var string
     */
    private $ruleString;

    /**
     * @var string
     */
    private $messageString;

    public function __construct(string $ruleString, string $messageString)
    {
        $this->ruleString    = $ruleString;
        $this->messageString = $messageString;
    }

    public function getRuleString() : string
    {
        return $this->ruleString;
    }

    public function getMessageString() : string
    {
        return $this->messageString;
    }
}
