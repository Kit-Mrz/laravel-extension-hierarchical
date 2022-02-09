<?php

namespace App\Repositories\{{RNT}};

class {{RNT}}RepositoryFactory
{
    /**
     * @desc 数据仓库
     * @return {{RNT}}Repository
     */
    public static function get{{RNT}}Repository() : {{RNT}}Repository
    {
        app()->singletonIf({{RNT}}Repository::class);

return app({{RNT}}Repository::class);
}

/**
* @desc 复杂数据仓库
* @return {{RNT}}RepositoryComplex
*/
public static function get{{RNT}}RepositoryComplex() : {{RNT}}RepositoryComplex
{
app()->singletonIf({{RNT}}Repository::class);

return app({{RNT}}RepositoryComplex::class);
}
}
