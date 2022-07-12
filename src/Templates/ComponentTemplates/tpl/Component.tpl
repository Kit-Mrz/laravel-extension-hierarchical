<?php

namespace App\Components\{{RNT}};

use App\Components\ComponentAbstract;

class {{RNT}} extends ComponentAbstract
{
    /**
     * @desc 获取接口类路径
     * @return string
     */
    public function getInterface() : string
    {
        // 返回接口
        return {{RNT}}Interface::class;;
    }

    /**
     * @desc 获取实现类路径
     * @return string
     */
    public function getImplement() : string
    {
        // 返回实现
        return {{RNT}}Impl::class;;;
    }

    /**
     * @desc 调用组件,为了便于IDEA识别代码提示
     * @param array $parameters
     * @param bool $shared
     * @return
     */
    public static function do(array $parameters = [], bool $shared = true)
    {
        return parent::do($parameters, $shared);
    }
}
