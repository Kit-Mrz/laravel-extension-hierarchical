<?php

namespace Mrzkit\LaravelExtensionHierarchical;

class Tester
{

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
        $result = $th->sharRepositoryCreation($name);
        dump($result);

        $name = 'RenewalSystem.HandleItem';

        $th     = new TemplateHandler();
        $result = $th->shardControllerCreation($name);
        dump($result);

        $th     = new TemplateHandler();
        $result = $th->sharServiceCreation($name);
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
