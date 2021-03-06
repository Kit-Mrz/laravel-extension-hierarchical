<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ComponentCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ControllerTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RepositoryTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RequestTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RouteTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ServiceTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\UnitTestTemplateCreator;

class Tester
{
    use TemplateTool;

    public static function testRunner()
    {
        $params1 = [
            "tableShard"    => 1,
            "shardCount"    => 2,
            "maxShardCount" => 64,
            "tablePrefix"   => env('DB_PREFIX', ""),
            "tableName"     => "renewal_payments",
            "controls"      => "CpcSystem.RenewalPayments",
        ];

        $params2 = [
            "tableShard"    => 0,
            "shardCount"    => 0,
            "maxShardCount" => 0,
            "tablePrefix"   => env('DB_PREFIX', ""),
            "tableName"     => "tags",
            "controls"      => "CpcSystem.Tag",
        ];

        dump(static::callCreator($params2));
    }

    public static function callCreator(array $params) : array
    {
        if ($params["tableShard"]) {
            $inputParams = [
                "tableShard"    => 1,
                "shardCount"    => $params["shardCount"] >= 2 ? $params["shardCount"] : 2,
                "maxShardCount" => 64,
                "tablePrefix"   => $params["tablePrefix"],
                "tableName"     => $params["tableName"],
                "controls"      => $params["controls"],
            ];
        } else {
            $inputParams = [
                "tableShard"    => 0,
                "shardCount"    => 0,
                "maxShardCount" => 0,
                "tablePrefix"   => $params["tablePrefix"],
                "tableName"     => $params["tableName"],
                "controls"      => $params["controls"],
            ];
        }

        if ( !static::validateControlName($inputParams["controls"])) {
            throw new \Exception("???????????????????????????: A.B ??? A.B.C ");
        }

        $tableInformation = new TableInformation($inputParams["tableName"], $inputParams["tablePrefix"], $inputParams["tableShard"], $inputParams["shardCount"], $inputParams["maxShardCount"]);

        $templateHandler = new TemplateHandler();

        $result = [];

        // Repository
        $creator = new RepositoryTemplateCreator($templateHandler, $tableInformation);

        $result["RepositoryTemplateCreator"] = $creator->handle();

        // Service
        $creator = new ServiceTemplateCreator($inputParams["controls"], $templateHandler, $tableInformation);

        $result["ServiceTemplateCreator"] = $creator->handle();

        // Request
        $creator = new RequestTemplateCreator($inputParams["controls"], $templateHandler, $tableInformation);

        $result["RequestTemplateCreator"] = $creator->handle();

        // Controller
        $creator = new ControllerTemplateCreator($inputParams["controls"], $templateHandler);

        $result["ControllerTemplateCreator"] = $creator->handle();

        // Route
        $creator = new RouteTemplateCreator($inputParams["controls"], $templateHandler);

        $result["RouteTemplateCreator"] = $creator->handle();

        // UnitTest
        $creator = new UnitTestTemplateCreator($inputParams["controls"], $templateHandler, $tableInformation);

        $result["UnitTestTemplateCreator"] = $creator->handle();

        return $result;
    }

    public static function callSimpleCreator(array $params) : array
    {
        if ($params["tableShard"]) {
            $inputParams = [
                "tableShard"    => 1,
                "shardCount"    => $params["shardCount"] >= 2 ? $params["shardCount"] : 2,
                "maxShardCount" => 64,
                "tablePrefix"   => $params["tablePrefix"],
                "tableName"     => $params["tableName"],
                "controls"      => $params["controls"],
            ];
        } else {
            $inputParams = [
                "tableShard"    => 0,
                "shardCount"    => 0,
                "maxShardCount" => 0,
                "tablePrefix"   => $params["tablePrefix"],
                "tableName"     => $params["tableName"],
                "controls"      => $params["controls"],
            ];
        }

        if ( !static::validateControlName($inputParams["controls"])) {
            throw new \Exception("???????????????????????????: A.B ??? A.B.C ");
        }

        $tableInformation = new TableInformation($inputParams["tableName"], $inputParams["tablePrefix"], $inputParams["tableShard"], $inputParams["shardCount"], $inputParams["maxShardCount"]);

        $templateHandler = new TemplateHandler();

        $result = [];

        // Repository
        $creator = new RepositoryTemplateCreator($templateHandler, $tableInformation);

        $result["RepositoryTemplateCreator"] = $creator->handle();

        // Service
        $creator = new ServiceTemplateCreator($inputParams["controls"], $templateHandler, $tableInformation);

        $result["ServiceTemplateCreator"] = $creator->handle();

        return $result;
    }

    public static function callComponentCreator(string $componentName) : array
    {
        $templateHandler = new TemplateHandler();

        $result = [];

        // Component
        $creator = new ComponentCreator($componentName, $templateHandler);

        $result["ComponentCreator"] = $creator->handle();

        return $result;
    }

}
