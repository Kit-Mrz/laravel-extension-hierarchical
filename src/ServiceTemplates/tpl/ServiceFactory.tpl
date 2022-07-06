<?php

namespace App\Services\{{NAMESPACE_PATH}}\{{RNT}};

class {{RNT}}ServiceFactory
{
    /**
     * @desc 服务
     * @return {{RNT}}Service
     */
    public static function get{{RNT}}Service() : {{RNT}}Service
    {
        app()->singletonIf({{RNT}}Service::class);

        return app({{RNT}}Service::class);
    }

    /**
     * @desc 服务
     * @return {{RNT}}BusinessService
     */
    public static function get{{RNT}}BusinessService() : {{RNT}}BusinessService
    {
        app()->singletonIf({{RNT}}BusinessService::class);

        return app({{RNT}}BusinessService::class);
    }
}
