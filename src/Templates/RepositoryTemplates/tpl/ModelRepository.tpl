<?php

namespace App\Repositories\{{RNT}};

use App\Supports\Cores\CrudRepository;

final class {{RNT}}Repository extends CrudRepository
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
            'relationName' => (function (array $where){
                return function ($query) use ($where){
                    // $fields = [];
                    // $query->select($fields);
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
            {{HANDLE_OUTPUT_TPL}}
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
            {{HANDLE_OUTPUT_RELATIONS_TPL}}
        ];

        return $item;
    }
}
