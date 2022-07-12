<?php

namespace App\Repositories\{{RNT}};

use App\Events\EloquentEvents\Creating;
use App\Events\EloquentEvents\Deleting;
use App\Events\EloquentEvents\Saving;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mrzkit\LaravelExtensionEloquent\Model\ShardModel;

final class {{RNT}} extends ShardModel
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
        {{FILL_ABLE_TPL}}
    ];

   /**
    * 字段注释
    *
    * @var array
    */
    protected static $fields = [
        {{FIELDS_TPL}}
    ];

    /**
     * @var int 获取最大分表数
     */
    protected $shardMaxCount = {{MAX_SHARD_COUNT}};

    /**
     * @var \int[][] 分表配置
     */
    protected $shardConfig = [
        {{SHARD_CONFIG_TPL}}
    ];

    /**
     * @desc 获取最大分表数
     * @return int
     */
    public function getShardMaxCount() : int
    {
        return $this->shardMaxCount;
    }

    /**
     * @desc 获取分表配置
     * @return \int[][]
     */
    public function getShardConfig() : array
    {
        return $this->shardConfig;
    }

    /**
     * @desc 获取当前分表数
     * @return int
     */
    public function getShardCount() : int
    {
        return count($this->getShardConfig());
    }
}
