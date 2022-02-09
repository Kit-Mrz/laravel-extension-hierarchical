<?php

namespace App\Repositories\{{RNT}};

use App\Events\EloquentEvents\Creating;
use App\Events\EloquentEvents\Deleting;
use App\Events\EloquentEvents\Saving;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mrzkit\LaravelExtensionEloquent\Model\CrudModel;

final class {{RNT}} extends CrudModel
{
    use HasFactory, SoftDeletes;

    /**
     * 与表关联的主键。
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 模型日期的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 默认值属性
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        {{FILLABLE}}
        'created_by', 'updated_by', 'deleted_by',
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * 字段注释
     *
     * @var array
     */
    protected static $attributeComments = [
        {{ATTRIBUTE_COMMENT}}
    ];

    /**
     * 映射 Eloquent 事件
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => Creating::class,
'updating' => Saving::class,
'deleting' => Deleting::class,
];
}
