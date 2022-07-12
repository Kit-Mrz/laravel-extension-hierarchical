<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ComponentCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RouteTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\UnitTestTemplateCreator;

class Tester
{
    public static function testRunner()
    {
        $params = [
            "tableShard" => 0,
            "prefix"     => env('DB_PREFIX', ""),
            "tableName"  => "tags",
            "controls"   => "CpcSystem.Tag",
        ];

        $params = [
            "tableShard"    => 1,
            "shardCount"    => 2,
            "maxShardCount" => 64,
            "tablePrefix"   => env('DB_PREFIX', ""),
            "tableName"     => "renewal_payments",
            "controls"      => "CpcSystem.RenewalPayments",
        ];

//        if ( !$this->validateControlName($controlName)) {
//            throw new \Exception("格式有误，参考格式: A.B 或 A.B.C ");
//        }

        $tableInformation = new TableInformation($params["tableName"], $params["tablePrefix"], $params["tableShard"], $params["shardCount"], $params["maxShardCount"]);

        $templateHandler = new TemplateHandler();

//        // Repository
//        $creator = new RepositoryTemplateCreator($tableInformation, $templateHandler);
//        dump($creator->handle());
//        // Service
//        $creator = new ServiceTemplateCreator($params["controls"], $tableInformation, $templateHandler);
//        dump($creator->handle());
//        // Request
//        $creator = new RequestTemplateCreator($params["controls"], $tableInformation, $templateHandler);
//        dump($creator->handle());
//        // Controller
//        $creator = new ControllerTemplateCreator($params["controls"], $templateHandler);
//        dump($creator->handle());
        // Route
        $creator = new RouteTemplateCreator($params["controls"], $templateHandler);
        dump($creator->handle());
        // UnitTest
        $creator = new UnitTestTemplateCreator($params["controls"], $tableInformation, $templateHandler);
        dump($creator->handle());
        // Component
        $creator = new ComponentCreator("Util", $templateHandler);
        dump($creator->handle());
    }

}
