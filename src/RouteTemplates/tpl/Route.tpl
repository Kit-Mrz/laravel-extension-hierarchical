Route::group(
    [
        'middleware' => '{{AUTH_MIDDLEWARE}}',
    ],
    function (Router $router){
        // {{RNT}} ******** ******** ******** ********  ******** ******** ******** ********
        $router->apiResource('{{LOWER_ROUTE_NAME}}', {{NAMESPACE_PATH}}\{{RNT}}Controls\{{RNT}}Controller::class)->names(
            [
                'index'   => Decorate::do()->internal('{{RNT}}列表'),
                'show'    => Decorate::do()->internal('{{RNT}}信息'),
                'store'   => Decorate::do()->internal('{{RNT}}添加'),
                'update'  => Decorate::do()->internal('{{RNT}}更新'),
                'destroy' => Decorate::do()->internal('{{RNT}}删除'),
            ]
        );

        $router->post('{{LOWER_ROUTE_NAME}}-ext/many', [{{NAMESPACE_PATH}}\{{RNT}}Controls\{{RNT}}Controller::class, 'many'])->name(Decorate::do()->external('{{RNT}}批量获取'));
        $router->post('{{LOWER_ROUTE_NAME}}-ext/many-destroy', [{{NAMESPACE_PATH}}\{{RNT}}Controls\{{RNT}}Controller::class, 'manyDestroy'])->name(Decorate::do()->external('{{RNT}}批量删除'));
        $router->post('{{LOWER_ROUTE_NAME}}-ext/many-store', [{{NAMESPACE_PATH}}\{{RNT}}Controls\{{RNT}}Controller::class, 'manyStore'])->name(Decorate::do()->external('{{RNT}}批量保存'));
        $router->post('{{LOWER_ROUTE_NAME}}-ext/many-update', [{{NAMESPACE_PATH}}\{{RNT}}Controls\{{RNT}}Controller::class, 'manyUpdate'])->name(Decorate::do()->external('{{RNT}}批量更新'));
});

//{{HERE}}
