<?php

namespace Mrzkit\LaravelExtensionHierarchical;

interface RequestTemplateRenderContract
{
    public function getRuleString() : string;

    public function getMessageString() : string;
}
