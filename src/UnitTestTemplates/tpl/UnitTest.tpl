<?php

namespace Tests\Feature\{{NAMESPACE_PATH}};

use Tests\Feature\BaseTest;
use App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\{{RNT}}Controller;

class {{RNT}}ControllerTest extends BaseTest
{
    /**
     * @desc {{RNT}}
     * @see {{RNT}}Controller::index()
     * @uri get:/{{RNT_ROUTE_PATH}}?page=1&perPage=20&orderType=-id
     */
    public function testIndex()
    {
        $query = [
            "page"      => 1,
            "perPage"   => 20,
            "orderType" => "+id",
        ];

        $uri = "/{{RNT_ROUTE_PATH}}" . '?' . http_build_query($query);

        $response = $this->get($uri, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }

    /**
     * @desc {{RNT}}
     * @see {{RNT}}Controller::store()
     * @uri post:/{{RNT_ROUTE_PATH}}
     */
    public function testStore()
    {
        $data = [
            "tenantId" => 1,
            "name"     => "jack",
            "type"     => 1,
            "color"    => "FFEEFF",
        ];

        $uri = "/{{RNT_ROUTE_PATH}}";

        $response = $this->post($uri, $data, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }

    /**
     * @desc {{RNT}}
     * @see {{RNT}}Controller::show()
     * @uri get:/{{RNT_ROUTE_PATH}}/{id}
     */
    public function testShow()
    {
        $id = 1;

        $uri = "/{{RNT_ROUTE_PATH}}/{$id}";

        $response = $this->get($uri, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }

    /**
     * @desc {{RNT}}
     * @see {{RNT}}Controller::update()
     * @uri put:/{{RNT_ROUTE_PATH}}/{id}
     */
    public function testUpdate()
    {
        $id = 2;

        $data = [
            "tenantId" => 1,
            "name"     => "jack",
            "type"     => 1,
            "color"    => "FFEEFF",
        ];

        $uri = "/{{RNT_ROUTE_PATH}}/{$id}";

        $response = $this->put($uri, $data, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }

    /**
     * @desc {{RNT}}
     * @see {{RNT}}Controller::destroy()
     * @uri delete:/{{RNT_ROUTE_PATH}}/{id}
     */
    public function testDestroy()
    {
        $id = 2;

        $data = [];

        $uri = "/{{RNT_ROUTE_PATH}}/{$id}";

        $response = $this->delete($uri, $data, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }

    /**
     * @desc {{RNT}}
     * @see {{RNT}}Controller::many()
     * @uri get:/{{RNT_ROUTE_PATH}}-ext/many
     */
    public function testMany()
    {
        $data = [
            "ids" => [1, 2, 3],
        ];

        $uri = "/{{RNT_ROUTE_PATH}}-ext/many";

        $response = $this->post($uri, $data, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }

    /**
     * @desc {{RNT}}
     * @see {{RNT}}Controller::batchDestroy()
     * @uri get:/{{RNT_ROUTE_PATH}}-ext/batch-destroy
     */
    public function testBatchDestroy()
    {
        $data = [
            "ids" => [1, 2, 3],
        ];

        $uri = "/{{RNT_ROUTE_PATH}}-ext/batch-destroy";

        $response = $this->post($uri, $data, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }

    /**
     * @desc {{RNT}}
     * @see {{RNT}}Controller::batchStore()
     * @uri get:/{{RNT_ROUTE_PATH}}-ext/batch-store
     */
    public function testBatchStore()
    {
        $data = [
            "batch" => [
                [
                    "tenantId" => 1,
                    "name"     => "jack1",
                    "type"     => 1,
                    "color"    => "FFEEFF",
                ],
                [
                    "tenantId" => 1,
                    "name"     => "jack2",
                    "type"     => 2,
                    "color"    => "FFEEFF",
                ],
            ],

        ];

        $uri = "/{{RNT_ROUTE_PATH}}-ext/batch-store";

        $response = $this->post($uri, $data, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }

    /**
     * @desc {{RNT}}
     * @see {{RNT}}Controller::batchUpdate()
     * @uri get:/{{RNT_ROUTE_PATH}}-ext/batch-update
     */
    public function testBatchUpdate()
    {
        $data = [
            "batch" => [
                [
                    "id"       => 4,
                    "tenantId" => 1,
                    "name"     => "jingjing1",
                    "type"     => 1,
                    "color"    => "F2F1F1",
                ],
                [
                    "id"       => 5,
                    "tenantId" => 1,
                    "name"     => "jingjing2",
                    "type"     => 2,
                    "color"    => "F1F1F1",
                ],
            ],

        ];

        $uri = "/{{RNT_ROUTE_PATH}}-ext/batch-update";

        $response = $this->post($uri, $data, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }
}