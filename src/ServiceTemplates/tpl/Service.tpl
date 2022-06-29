<?php

namespace App\Services\{{NAMESPACE_PATH}}\{{RNT}};

use App\Components\Back\Back;
use App\Exceptions\Business\EmptyException;
use App\Exceptions\Business\InvalidArgumentException;
use App\Exceptions\Business\NotExistsException;
use App\Exceptions\Fails\DeleteException;
use App\Exceptions\Fails\UpdateException;
use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY_NAME}};
use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY}};
use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY}}Factory;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Mrzkit\LaravelExtensionEloquent\Contracts\ControlServiceContract;

class {{RNT}}Service implements ControlServiceContract
{
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
        ];

        $cacheKey = "{{RNT}}Service:index";

        $list = Cache::remember($cacheKey, 1, function () use($inputParams) {
            //
            $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}();

            $fields = {{REPOSITORY}}::getSnake();

            $relations = [];

            $paginateParams = [
                'page'    => $inputParams['page'],
                'perPage' => $inputParams['perPage'],
            ];

            $orderConfig = ['orderKey' => $inputParams['orderType']];

            $paginator = $repository->retrieve($fields, $relations, $paginateParams, $orderConfig, function (Builder $query) use($inputParams){
                //
                if (isset($inputParams['???'])) {
                    //$query->where('??', $inputParams['???']);
                }
            });

            $list = Back::do()->retrieveIterator($paginator, function ({{REPOSITORY_NAME}} $object){
                //
                $row = $object->toArray();
                //
                $item = {{REPOSITORY}}::handleOutput($row);

                return $item;
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
        $params = Arr::only($params, [
            {{HUMP_FIELDS}}
        ]);

        if (empty($params)) {
            throw new EmptyException();
        }

        $inputParams = [
            {{CODE_TPL_STORE}}
        ];

        $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}();

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
        $fields = {{REPOSITORY}}::getSnake();

        $object = $this->info($id, $fields);

        $row = $object->toArray();

        $item = {{REPOSITORY}}::handleOutput($row);

        return $item;
    }

    /**
     * @desc 更新
     * @param int $id 主键
     * @param array $params 数据
     * @return bool
     */
    public function update(int $id, array $params) : bool
    {
        $inputParams = Arr::only($params, [
            {{HUMP_FIELDS}}
        ]);

        $data = [];

        {{CODE_TPL_UPDATE}}

        if (empty($inputParams)) {
            throw new EmptyException();
        }

        $object = $this->info($id);

        $updated = $object->update($data);

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
        $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}();

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
        if (1 > count($ids)) {
            throw new InvalidArgumentException();
        }

        $fields = {{REPOSITORY}}::getSnake();

        $repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}();

        $objects = $repository->many($ids, $fields);

        $list = [];

        foreach ($objects->getIterator() as $row) {
            $row    = $row->toArray();
            $list[] = {{REPOSITORY}}::handleOutput($row);
        }

        return $list;
    }
}
