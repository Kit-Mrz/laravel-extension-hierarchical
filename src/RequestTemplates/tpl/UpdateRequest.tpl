<?php

namespace App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\Requests;

use App\Supports\Cores\FormRequest;

class {{RNT}}UpdateRequest extends FormRequest
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
        {{RULE_STRING}}
    }

    /**
     * @desc 规则消息
     * @return array
     */
    public static function getMessages() : array
    {
        {{MESSAGE_STRING}}
    }
}
