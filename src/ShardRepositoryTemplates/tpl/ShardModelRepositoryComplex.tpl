<?php

namespace App\Repositories\{{RNT}};

use App\Supports\Cores\ShardRepository;

final class {{RNT}}RepositoryComplex extends ShardRepository
{
    public function __construct({{RNT}} ${{RNT}})
    {
        $this->setModel(${{RNT}});
}

/**
* @desc 关联查询配置
* @return \Closure[]
*/
public function relationConfig() : array
{
return [
'relationName' => (function ($where){
return function ($query) use ($where){

};
}),
];
}

/**
* @desc 排序配置
* @param string $orderTable
* @return \string[][]
*/
public function orderConfig(string $orderTable = '') : array
{
return [
'-id' => [
'orderTable' => $orderTable,
'key'        => 'id',
'value'      => 'desc',
],
'+id' => [
'orderTable' => $orderTable,
'key'        => 'id',
'value'      => 'asc',
],
];
}

/**
* @desc 输出处理
* @param array $row
* @return array
*/
public static function handleOutput(array $row) : array
{
$item = [
{{CODE_TPL_ITEM}}
];

return $item;
}

/**
* @desc 输出处理-带关联数据
* @param array $row
* @return array
*/
public static function handleOutputRelations(array $row) : array
{
$item = [
{{CODE_TPL_ITEM_HUMP}}
];

return $item;
}
}
