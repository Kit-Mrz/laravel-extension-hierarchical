<?php

namespace Tests\Feature\{{NAMESPACE_PATH}};

use Tests\Feature\BaseTest;
use App\Http\Controllers\{{NAMESPACE_PATH}}\{{RNT}}Controls\{{RNT}}Controller;

class {{RNT}}ControllerTest extends BaseTest
{
    /**
     * 路由前缀
     */
    const PREFIX = "";

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

        $uri = self::PREFIX . "/{{RNT_ROUTE_PATH}}" . '?' . http_build_query($query);

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
            {{UNIT_TEST_STORE_CODE}}
        ];

        $uri = self::PREFIX . "/{{RNT_ROUTE_PATH}}";

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

        $uri = self::PREFIX . "/{{RNT_ROUTE_PATH}}/{$id}";

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
            {{UNIT_TEST_STORE_CODE}}
        ];

        $uri = self::PREFIX . "/{{RNT_ROUTE_PATH}}/{$id}";

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

        $uri = self::PREFIX . "/{{RNT_ROUTE_PATH}}/{$id}";

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

        $uri = self::PREFIX . "/{{RNT_ROUTE_PATH}}-ext/many";

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

        $uri = self::PREFIX . "/{{RNT_ROUTE_PATH}}-ext/batch-destroy";

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
                   {{UNIT_TEST_STORE_CODE}}
                ],
            ],

        ];

        $uri = self::PREFIX . "/{{RNT_ROUTE_PATH}}-ext/batch-store";

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
                    "id" => 0,
                   {{UNIT_TEST_STORE_CODE}}
                ],
            ],

        ];

        $uri = self::PREFIX . "/{{RNT_ROUTE_PATH}}-ext/batch-update";

        $response = $this->post($uri, $data, ["Authorization" => $this->getToken(),]);

        $response->dump();

        $response->assertStatus(200);
    }
}
