
# laravel extension hierarchical 脚手


````PHP
<?php
    $inputParams = [
            'type' => $params['type'] ?? null,
            'name' => $params['name'] ?? null,
        ];

        $result = [];

        $templateHandler = new TemplateHandler();

        switch ($inputParams['type']) {
            case 'repository': // 模型
                $result = $templateHandler->repositoryCreation($inputParams['name']);
                break;
            case 'shard': // 分表模型
                $result = $templateHandler->shardRepositoryCreation($inputParams['name']);
                break;
            case 'controller': // 控制器
                $result = $templateHandler->controllerCreation($inputParams['name']);
                break;
            case 'shardController': // 分表控制器
                $result = $templateHandler->shardControllerCreation($inputParams['name']);
                break;
            case 'request': // 请求验证
                $result = $templateHandler->requestCreation($inputParams['name']);
                break;
            case 'service': // 服务层
                $result = $templateHandler->serviceCreation($inputParams['name']);
                break;
            case 'shardService': // 分表服务层
                $result = $templateHandler->shardServiceCreation($inputParams['name']);
                break;
            case 'route': // 路由替换
                $result = $templateHandler->routeCreation($inputParams['name']);
                break;
            case 'controls': 
                $result['controller'] = $templateHandler->controllerCreation($inputParams['name']);
                $result['request']    = $templateHandler->requestCreation($inputParams['name']);
                $result['service']    = $templateHandler->serviceCreation($inputParams['name']);
                $result['route']      = $templateHandler->routeCreation($inputParams['name']);
                break;
            case 'shardControls':
                $result['shardController'] = $templateHandler->shardControllerCreation($inputParams['name']);
                $result['request']         = $templateHandler->requestCreation($inputParams['name']);
                $result['shardService']    = $templateHandler->shardServiceCreation($inputParams['name']);
                $result['route']           = $templateHandler->routeCreation($inputParams['name']);
                break;
            default:
                throw new \InvalidArgumentException("Type not default: {$inputParams['type']}");
        }
````
