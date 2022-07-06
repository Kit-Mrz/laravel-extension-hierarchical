<?php

namespace App\Components;

interface ComponentContract
{
    /**
     * @desc 调用组件的静态方法
     * @param array $parameters
     * @param bool $shared
     * @return mixed
     */
    public static function do(array $parameters = [], bool $shared = true);

    /**
     * @desc 获取接口类路径
     * @return string
     */
    public function getInterface() : string;

    /**
     * @desc 获取实现类路径
     * @return string
     */
    public function getImplement() : string;

    /**
     * @desc 绑定和创建实例
     * @param array $parameters
     * @param bool $shared
     * @return mixed
     */
    public function makeBind(array $parameters = [], bool $shared = true);
}
