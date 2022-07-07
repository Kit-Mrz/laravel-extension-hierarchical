<?php

namespace App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls;

use App\Components\Back\Back;
use App\Components\ToolHelper\ToolHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\Requests\{{RNT}}IndexRequest;
use App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\Requests\{{RNT}}ManyRequest;
use App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\Requests\{{RNT}}StoreRequest;
use App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\Requests\{{RNT}}UpdateRequest;
use App\Services\{{NAMESPACE_PATH}}\{{RNT}}\{{RNT}}Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class {{RNT}}Controller extends Controller
{
    /**
     * @var {{RNT}}Service
     */
    private ${{RNT}}Service;

    public function __construct({{RNT}}Service ${{RNT}}Service)
    {
        $tenantId = ToolHelper::do()->activeUser()->getTenantId();

        $this->{{RNT}}Service = ${{RNT}}Service->setFactorId($tenantId)->setTenantId($tenantId);
    }

    /**
     * @desc 列表
     * @uri get /path?desc=1
     *
     * @return JsonResponse
     */
    public function index({{RNT}}IndexRequest $request) : JsonResponse
    {
        $params = $request->validated();

        $result = $this->{{RNT}}Service->index($params);

        return Back::do()->success($result);
    }

    /**
     * @desc 保存
     * @uri post /path?desc=1
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store({{RNT}}StoreRequest $request) : JsonResponse
    {
        $params = $request->validated();

        $result = $this->{{RNT}}Service->store($params);

        return Back::do()->success($result);
    }

    /**
     * @desc 详情
     * @uri get /path?desc=1
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id) : JsonResponse
    {
        $result = $this->{{RNT}}Service->show($id);

        return Back::do()->success($result);
    }

    /**
     * @desc 更新
     * @uri put /path?desc=1
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update({{RNT}}UpdateRequest $request, int $id) : JsonResponse
    {
        $params = $request->validated();

        $updated = $this->{{RNT}}Service->update($id, $params);

        $result = [
            'updated' => $updated
        ];

        return Back::do()->success($result);
    }

    /**
     * @desc 删除
     * @uri delete /path?desc=1
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id) : JsonResponse
    {
        $deleted = $this->{{RNT}}Service->destroy($id);

        $result = [
            'deleted' => $deleted
        ];

        return Back::do()->success($result);
    }

    /**
     * @desc 多个
     * @uri get /path?desc=1
     *
     * @return JsonResponse
     */
    public function many({{RNT}}ManyRequest $request) : JsonResponse
    {
        $params = $request->validated();

        $result = $this->{{RNT}}Service->many($params["ids"]);

        return Back::do()->success($result);
    }
}
