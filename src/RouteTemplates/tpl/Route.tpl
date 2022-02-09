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
});

//{{HERE}}
