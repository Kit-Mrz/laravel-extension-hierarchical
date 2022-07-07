<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\ControllerTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RepositoryTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RequestTemplateCreator;
use Mrzkit\LaravelExtensionHierarchical\TemplateCreators\RouteTemplateCreator;

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
        $creator = new RouteTemplateCreator($params["controls"], $params["tableName"], $params["prefix"]);
        dump($creator->handle());
    }

    public static function testRepositoryCreator()
    {
        $creator = new RepositoryTemplateCreator("tags", "rnt_");
        dump($creator->handle());
    }

    public static function testControllerTemplateCreator()
    {
        $creator = new ControllerTemplateCreator("UserSystem.Tags");
        dump($creator->handle());
    }

    public static function testRequestTemplateCreator()
    {
        $creator = new RequestTemplateCreator("UserSystem.Tags");
        dump($creator->handle());
    }

    public static function testNormal()
    {
        $name = 'Tags';

        $th     = new TemplateHandler();
        $result = $th->repositoryCreation($name);
        dump($result);

        $name = 'RenewalSystem.Tag';

        $th     = new TemplateHandler();
        $result = $th->controllerCreation($name);
        dump($result);

        $th     = new TemplateHandler();
        $result = $th->serviceCreation($name);
        dump($result);

        $th     = new TemplateHandler();
        $result = $th->requestCreation($name);
        dump($result);

        $th     = new TemplateHandler();
        $result = $th->formRequestCreation("FormRequest");
        dump($result);
    }

    public static function testShard()
    {
        $name = 'HandleItems';

        $th     = new TemplateHandler();
        $result = $th->shardRepositoryCreation($name);
        dump($result);

        $name = 'RenewalSystem.HandleItem';

        $th     = new TemplateHandler();
        $result = $th->shardControllerCreation($name);
        dump($result);

        $th     = new TemplateHandler();
        $result = $th->shardServiceCreation($name);
        dump($result);

        $th     = new TemplateHandler();
        $result = $th->requestCreation($name);
        dump($result);

        $th     = new TemplateHandler();
        $result = $th->formRequestCreation("FormRequest");
        dump($result);
    }

    public static function testComponent()
    {
        $name = 'TestCom';

        $th     = new TemplateHandler();
        $result = $th->componentCreation($name);
        dump($result);
    }

}
