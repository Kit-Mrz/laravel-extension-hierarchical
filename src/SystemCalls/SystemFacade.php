<?php

namespace Mrzkit\LaravelExtensionHierarchical\SystemCalls;

class SystemFacade
{
    private static $factorId;
    private static $tenantId;
    private static $factory;

    public function __construct($factory, int $factorId = 0, int $tenantId = 0)
    {
        static::$factorId = $factorId;
        static::$tenantId = $tenantId;
        static::$factory  = $factory;
    }

    /**
     * @desc
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $o = static::$factory::$name($arguments);

        if (method_exists($o, 'setFactorId')) {
            $o->setFactorId(static::$factorId);
        }
        if (method_exists($o, 'setTenantId')) {
            $o->setTenantId(static::$tenantId);
        }

        return $o;
    }

    /**
     * @desc
     * @param string $className
     * @param int $factorId
     * @param int $tenantId
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public static function callFactory(string $className, int $factorId, int $tenantId)
    {
        app()->singletonIf($className);

        $factory = app($className);

        return app(static::class, ['factory' => $factory, 'factorId' => $factorId, 'tenantId' => $tenantId]);
    }
}
