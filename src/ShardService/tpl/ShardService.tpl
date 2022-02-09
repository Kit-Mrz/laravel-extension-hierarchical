<?php

namespace App\Services\{{NAMESPACE_PATH}}\{{RNT}};

use App\Components\Back\Back;
use App\Exceptions\Business\EmptyException;
use App\Exceptions\Business\NotExistsException;
use App\Exceptions\Fails\DeleteException;
use App\Exceptions\Fails\UpdateException;
use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY_NAME}};
use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY}};
use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY}}Factory;
use App\Supports\Spreadsheet\TransObjects\TenantTrans;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Mrzkit\LaravelExtensionEloquent\Contracts\ControlServiceContract;
use Mrzkit\LaravelExtensionEloquent\FactorTrait;

class {{RNT}}Service implements ControlServiceContract
{
    use FactorTrait;
    use TenantTrans;

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
"tenantId"  => (int) ($params["tenantId"]),
];

$list = Cache::remember('{{RNT}}Service:index', 1, function () use($inputParams) {
//
$repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}()->setFactor($this->getFactorId());

$fields = [
'id',
{{FILLABLE}}
];

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
}, function (Builder $query){
// 查看SQL语句
// dd($query->toSql());
});

$list = Back::do()->retrieveIterator($paginator, function ({{REPOSITORY_NAME}} $obj, int $index){
// 转换数组
$row = $obj->toArray();
// 处理输出
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

$repository = {{REPOSITORY}}Factory::get{{REPOSITORY}}()->setFactor($this->getFactorId());

$obj = $repository->create($inputParams);

return $obj->toArray();
}

/**
* @desc 展示
* @param int $id 主键
* @return array
*/
public function show(int $id)
{
$fields = [
'id', {{FILLABLE}}
];

$obj = $this->info($id, $fields);

$row = $obj->toArray();

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

if (empty($inputParams)) {
throw new EmptyException();
}

$data = [];

{{CODE_TPL_UPDATE}}

$obj = $this->info($id);

$updated = $obj->update($data);

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
$obj = $this->info($id);

$deleted = $obj->delete();

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

$obj = $repository->info($id, $fields, $relations, $before);

if (is_null($obj)) {
throw new NotExistsException("{{RNT}}");
}

return $obj;
}
}
