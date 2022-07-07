<?php

namespace Mrzkit\LaravelExtensionHierarchical;

interface RequestTemplateRender
{
    public function getRuleString() : string;

    public function getMessageString() : string;
}
