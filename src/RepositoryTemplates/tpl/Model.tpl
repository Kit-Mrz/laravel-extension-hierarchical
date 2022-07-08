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
     * 映射 Eloquent 事件
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => Creating::class,
        'updating' => Saving::class,
        'deleting' => Deleting::class,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
    ];

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        {{FILLABLE}}
    ];

   /**
    * 字段注释
    *
    * @var array
    */
    protected static $fields = [
        {{FIELDS}}
    ];

}
