<?php

namespace App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\Requests;

use App\Supports\Cores\FormRequest;

class {{RNT}}ManyRequest extends FormRequest
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
        return [
            'ids.*' => "required|integer|between:0,4294967295",
        ];
    }

    /**
     * @desc 规则消息
     * @return array
     */
    public static function getMessages() : array
    {
        return [
            'ids.required' => 'ID必填',
            'ids.integer'  => 'ID必须是整数',
            'ids.between'  => 'ID超出范围',
        ];
    }
}
