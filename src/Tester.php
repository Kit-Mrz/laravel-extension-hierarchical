<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ComponentCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ControllerTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RepositoryTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RequestTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RouteTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ServiceTemplateCreator;

class Tester
{
    public static function testRunner()
    {
        $params = [
            "shard"     => 0,
            "prefix"    => env('DB_PREFIX', ""),
            "tableName" => "renewal_payments",
            "controls"  => "UserSystem.RenewalPayments",
        ];

        // Repository
        // Controller
        // Request
        // Service
        // Route
//        $creator = new RepositoryTemplateCreator($params["tableName"], $params["prefix"]);
//        dump($creator->handle());
//        $creator = new RequestTemplateCreator($params["controls"], $params["tableName"], $params["prefix"]);
//        dump($creator->handle());
//        $creator = new ControllerTemplateCreator($params["controls"], $params["tableName"], $params["prefix"]);
//        dump($creator->handle());
//        $creator = new RouteTemplateCreator($params["controls"], $params["tableName"], $params["prefix"]);
//        dump($creator->handle());
//        $creator = new ServiceTemplateCreator($params["controls"], $params["tableName"], $params["prefix"], $params["shard"]);
//        dump($creator->handle());
        $creator = new ComponentCreator("Util");
        dump($creator->handle());
    }
}
