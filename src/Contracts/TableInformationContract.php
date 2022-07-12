<?php

namespace Mrzkit\LaravelExtensionHierarchical\Contracts;

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

    /**
     * @desc
     * @return int
     */
    public function getMaxShardCount() : int;

    /**
     * @desc
     * @param int $n
     * @return bool
     */
    public function isPower(int $n) : bool;

    /**
     * @desc
     * @return array
     */
    public function getSuffixCount() : array;

    /**
     * @desc
     * @return array
     */
    public function getShardCountConfig() : array;
}
