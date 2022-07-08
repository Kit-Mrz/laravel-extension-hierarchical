<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Database\Factories\TagFactory;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ControllerTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RepositoryTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RequestTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RouteTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ServiceTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\UnitTestTemplateCreator;

class Tester
{
    public static function testRunner()
    {
        $params = [
            "shard"     => 1,
            "prefix"    => env('DB_PREFIX', ""),
            "tableName" => "renewal_payments",
            "controls"  => "UserSystem.RenewalPayments",
        ];

        $params = [
            "shard"     => 1,
            "prefix"    => env('DB_PREFIX', ""),
            "tableName" => "renewal_payments",
            "controls"  => "UserSystem.Deep.RenewalPayments",
        ];

        $params = [
            "shard"     => 0,
            "prefix"    => env('DB_PREFIX', ""),
            "tableName" => "tags",
            "controls"  => "CpcSystem.Tag",
        ];

        // UnitTest
        $creator = new UnitTestTemplateCreator($params["controls"], $params["tableName"], $params["prefix"]);
        dump($creator->handle());

//        // Repository
//        $creator = new RepositoryTemplateCreator($params["tableName"], $params["prefix"]);
//        dump($creator->handle());
//        // Service
//        $creator = new ServiceTemplateCreator($params["controls"], $params["tableName"], $params["prefix"], $params["shard"]);
//        dump($creator->handle());
//        // Controller
//        $creator = new ControllerTemplateCreator($params["controls"], $params["tableName"], $params["prefix"]);
//        dump($creator->handle());
//        // Request
//        $creator = new RequestTemplateCreator($params["controls"], $params["tableName"], $params["prefix"]);
//        dump($creator->handle());
//        // Route
//        $creator = new RouteTemplateCreator($params["controls"], $params["tableName"], $params["prefix"]);
//        dump($creator->handle());
        // Component
//        $creator = new ComponentCreator("Util");
//        dump($creator->handle());
    }

}
