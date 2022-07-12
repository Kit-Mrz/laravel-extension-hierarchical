<?php

namespace App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\Requests;

use App\Supports\Cores\FormRequest;

class {{RNT}}BatchUpdateRequest extends FormRequest
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
            {{REQUEST_BATCH_UPDATE_RULE_TPL}}
        ];
    }

    /**
     * @desc 规则消息
     * @return array
     */
    public static function getMessages() : array
    {
        return [
            {{REQUEST_BATCH_UPDATE_MESSAGE_TPL}}
        ];
    }
}
