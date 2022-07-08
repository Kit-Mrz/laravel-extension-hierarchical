<?php

namespace App\Components;

use Illuminate\Contracts\Container\BindingResolutionException;

abstract class ComponentAbstract implements ComponentContract
{
    /**
     * @desc 调用组件
     * @param array $parameters
     * @param bool $shared
     * @return mixed|void
     */
    public static function do(array $parameters = [], bool $shared = true)
    {
        // 单例模式
        app()->singletonIf(static::class);

        // 创建实例
        return app(static::class)->makeBind($parameters, $shared);
    }

    /**
     * @desc 绑定和创建实例
     * @param array $parameters
     * @param bool $shared 是否单例
     * @return mixed
     * @throws BindingResolutionException
     */
    final public function makeBind(array $parameters = [], bool $shared = true)
    {
        // 绑定
        app()->bindIf($this->getInterface(), $this->getImplement(), $shared);

        // 创建
        return app()->make($this->getInterface(), $parameters);
    }
}
