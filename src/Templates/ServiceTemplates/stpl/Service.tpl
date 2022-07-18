<?php

namespace App\Services\{{NAMESPACE_PATH}}\{{RNT}};

use App\Cache\CacheManagerFactory;
use App\Components\Back\Back;
use App\Exceptions\Business\EmptyException;
use App\Exceptions\Business\NotExistsException;
use App\Exceptions\Fails\CreateException;
use App\Exceptions\Fails\DeleteException;
use App\Exceptions\Fails\UpdateException;
use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY_NAME}};
use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY}}Factory;
use App\Supports\Cores\TenantShareTrait;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Mrzkit\LaravelExtensionEloquent\Contracts\ControlServiceContract;

class {{RNT}}Service implements ControlServiceContract
{
    use TenantShareTrait;

    /**
     * @desc 基本列表
     * @param array $params 查询参数
     * @return mixed
     */
    public function index(array $params)
    {
        $inputParams = [
            "page"      => (int) ($params["page"] ?? 1),
            "perPage"   => (int) ($params["perPage"] ?? 20),
            "orderType" => (string) ($params['orderType'] ?? "-id"),
            "tenantId"  => $this->getTenantId(),
        ];

        $cacheKey = CacheManagerFactory::getManager()->rta($this->getTenantId(), __METHOD__, $inputParams);

        $list = Cache::remember($cacheKey, 2, function () use($inputParams) {
            //
            $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}()->setFactor($this->getFactorId());

            $fields = {{REPOSITORY_NAME}}::getSnake();

            $relations = [];

            $paginateParams = [
                'page'    => $inputParams['page'],
                'perPage' => $inputParams['perPage'],
            ];

            $orderConfig = ['orderKey' => $inputParams['orderType']];

            $paginator = $repository->retrieve($fields, $relations, $paginateParams, $orderConfig, function (Builder $query) use($inputParams){
                //
                if (isset($inputParams['tenantId'])) {
                    $query->where('tenant_id', $inputParams['tenantId']);
                }
            });

            $renderService = {{RNT}}ServiceFactory::get{{RNT}}RenderService();

            $list = Back::do()->retrieveIterator($paginator, function ({{REPOSITORY_NAME}} $object) use ($renderService){
                //
               return $renderService->handle($object);
            });

            return $list;
        });

        return $list;
    }

    /**
     * @desc 保存
     * @param array $params 数据
     * @return array
     */
    public function store(array $params) : array
    {
        $inputParams = [
            {{SERVICE_STORE_TPL}}
        ];

        $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}()->setFactor($this->getFactorId());

        $object = $repository->create($inputParams);

        return $object->toArray();
    }

    /**
     * @desc 展示
     * @param int $id 主键
     * @return array
     */
    public function show(int $id) : array
    {
        $fields = {{REPOSITORY_NAME}}::getSnake();

        $object = $this->info($id, $fields);

        $renderService = {{RNT}}ServiceFactory::get{{RNT}}RenderService();

        return $renderService->handle($object);
    }

    /**
     * @desc 更新
     * @param int $id 主键
     * @param array $params 数据
     * @return bool
     */
    public function update(int $id, array $params) : bool
    {
        $tempParams = [];

        {{SERVICE_UPDATE_TPL}}

        if (empty($tempParams)) {
            throw new EmptyException();
        }

        $object = $this->info($id);

        $updated = $object->update($tempParams);

        if ( !$updated) {
            throw new UpdateException();
        }

        return $updated;
    }

    /**
     * @desc 删除
     * @param int $id
     * @return bool
     */
    public function destroy(int $id) : bool
    {
        $object = $this->info($id);

        $deleted = $object->delete();

        if ( !$deleted) {
            throw new DeleteException();
        }

        return $deleted;
    }

    /**
     * @desc 详情
     * @param int $id
     * @param array|string[] $fields
     * @param array $relations
     * @param Closure|null $before
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object
     */
    public function info(int $id, array $fields = ['id'], array $relations = [], Closure $before = null)
    {
        $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}()->setFactor($this->getFactorId());

        $object = $repository->info($id, $fields, $relations, $before);

        if (is_null($object)) {
            throw new NotExistsException("{{RNT}}");
        }

        return $object;
    }

    /**
     * @desc 多个
     * @param array $ids
     * @return array
     */
    public function many(array $ids) : array
    {
        $ids = collect($ids)->filter(function ($item){
            return $item > 0;
        });

        if ($ids->isEmpty()) {
            return [];
        }

        $ids = $ids->toArray();

        $fields = {{REPOSITORY_NAME}}::getSnake();

        $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}()->setFactor($this->getFactorId());

        $objects = $repository->many($ids, $fields);

        $list = [];

        $renderService = {{RNT}}ServiceFactory::get{{RNT}}RenderService();

        foreach ($objects->getIterator() as $object) {
            $list[] = $renderService->handle($object);
        }

        return $list;
    }

     /**
     * @desc 批量删除
     * @param int $id
     * @return int
     */
    public function batchDestroy(array $ids) : int
    {
        $ids = collect($ids)->filter(function ($item){
            return $item > 0;
        });

        if ($ids->isEmpty()) {
            return 0;
        }

        $ids = $ids->toArray();

        $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}()->setFactor($this->getFactorId());

        return $repository->batchDestroy($ids);
    }

    /**
     * @desc 批量保存
     * @param array $params 数据
     * @return bool
     */
    public function batchStore(array $params) : bool
    {
        $inputParams = [];

        foreach ($params as $param) {
            $inputParams[] = [
                {{SERVICE_BATCH_STORE_TPL}}
            ];
        }

        if (empty($inputParams)) {
            throw new EmptyException();
        }

        $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}()->setFactor($this->getFactorId());

        $created = $repository->fastBatchCreate($inputParams);

        if ( !$created) {
            throw new CreateException();
        }

        return $created;
    }

    /**
     * @desc 批量更新
     * @param array $params 数据
     * @return int
     */
    public function batchUpdate(array $params) : int
    {
        $inputParams = [];

        foreach ($params as $param) {
            $tempParams = [];

             if (isset($param["id"])) {
                $tempParams["_id"] = (int) $param["id"];
             }

             if (isset($param["factor"])) {
                $tempParams["_factor"] = (int) $param["factor"];
             }

            {{SERVICE_BATCH_UPDATE_TPL}}

            if ( !empty($tempParams)) {
                 if ( !isset($tempParams["_factor"])) {
                    $tempParams["_factor"] = $this->getFactorId();
                }

                $inputParams[] = $tempParams;
            }
        }

        if (empty($inputParams)) {
            throw new EmptyException();
        }

       $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}()->setFactor($this->getFactorId());

        $objects = $repository->safeBatchUpdate($inputParams);

        return count($objects);
    }
}
