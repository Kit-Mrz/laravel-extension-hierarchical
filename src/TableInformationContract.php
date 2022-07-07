<?php

namespace Mrzkit\LaravelExtensionHierarchical;

interface TableInformationContract
{
    /**
     * @return string
     */
    public function getTablePrefix() : string;

    /**
     * @return string
     */
    public function getTableName() : string;

    /**
     * @return string
     */
    public function getTableFullName() : string;

    /**
     * @return bool
     */
    public function getTableShard() : bool;

    /**
     * @return array
     */
    public function getTableFullColumns() : array;

    /**
     * @return array
     */
    public function getTableFields() : array;

    /**
     * @desc
     * @return string
     */
    public function getTableSuffix() : string;
}
