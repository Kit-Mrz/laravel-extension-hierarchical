<?php

namespace App\Supports\Cores;

use UnexpectedValueException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;

abstract class FormRequest extends HttpFormRequest
{
    /**
     * @var string[] 规则库
     */
    protected static $regexRules = [
    ];

    /**
     * @Override
     * @desc 处理失败的验证
     * @param Validator $validator
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();

        throw new UnexpectedValueException($error);
    }

    /**
     * 验证规则
     *
     * @return array
     */
    final public function rules()
    {
        return static::getRules();
    }

    /**
     * 规则信息
     *
     * @return array
     */
    final public function messages()
    {
        return static::getMessages();
    }

    /**
     * @desc 定义规则
     * @return array
     */
    abstract public static function getRules() : array;

    /**
     * @desc 定义错误消息
     * @return array
     */
    abstract public static function getMessages() : array;

    /**
     * @desc 获取规则库
     * @param string $ruleName
     * @return string|null
     */
    public static function regexRule(string $ruleName)
    {
        return static::$regexRules[$ruleName] ?? null;
    }
}
