<?php

namespace Mrzkit\LaravelExtensionHierarchical\Contracts;

interface RequestTemplateRenderContract
{
    public function getRuleString() : string;

    public function getMessageString() : string;
}
