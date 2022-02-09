<?php

namespace App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\Requests;

use App\Supports\Cores\FormRequest;

class {{RNT}}IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * @desc 验证规则
     * @return array
     */
    public static function getRules() : array
    {
        $orderType = join(',', ['-id', '+id']);

        return [
            'page'    => 'required|integer|between:0,10000',
            'perPage' => 'required|integer|between:2,10000',
            'orderType'  => "string|in:{$orderType}",
        ];
    }

    /**
     * @desc 规则消息
     * @return array
     */
    public static function getMessages() : array
    {
        return [
            'page.required' => '页码必填',
            'page.integer'  => '页码必须是整数',
            'page.between'  => '页码超出范围',

            'perPage.required' => '每页数必填',
            'perPage.integer'  => '每页数必须是整数',
            'perPage.between'  => '每页数超出范围',

            'orderType.string' => '排序类型格式错误',
            'orderType.in'     => '排序类型超出范围',
        ];
    }
}
