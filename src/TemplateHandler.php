<?php

namespace Mrzkit\LaravelExtensionHierarchical;

use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\Component;
use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\ComponentAbstract;
use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\ComponentContract;
use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\ComponentImpl;
use Mrzkit\LaravelExtensionHierarchical\ComponentTemplates\ComponentInterface;
use Mrzkit\LaravelExtensionHierarchical\ControllerTemplates\Controller;
use Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates\Model;
use Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates\ModelRepository;
use Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates\ModelRepositoryComplex;
use Mrzkit\LaravelExtensionHierarchical\RepositoryTemplates\ModelRepositoryFactory;
use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\IndexRequest;
use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\ManyRequest;
use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\StoreRequest;
use Mrzkit\LaravelExtensionHierarchical\RequestTemplates\UpdateRequest;
use Mrzkit\LaravelExtensionHierarchical\RouteTemplates\Route;
use Mrzkit\LaravelExtensionHierarchical\RouteTemplates\RouteReplace;
use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\BusinessService;
use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\Service;
use Mrzkit\LaravelExtensionHierarchical\ServiceTemplates\ServiceFactory;
use Mrzkit\LaravelExtensionHierarchical\ShardControllerTemplates\ShardController;
use Mrzkit\LaravelExtensionHierarchical\ShardRepositoryTemplates\ShardModel;
use Mrzkit\LaravelExtensionHierarchical\ShardRepositoryTemplates\ShardModelRepository;
use Mrzkit\LaravelExtensionHierarchical\ShardRepositoryTemplates\ShardModelRepositoryComplex;
use Mrzkit\LaravelExtensionHierarchical\ShardRepositoryTemplates\ShardModelRepositoryFactory;
use Mrzkit\LaravelExtensionHierarchical\ShardService\ShardService;
use Mrzkit\LaravelExtensionHierarchical\ShardService\ShardServiceBusinessService;
use Mrzkit\LaravelExtensionHierarchical\ShardService\ShardServiceFactory;
use Mrzkit\LaravelExtensionHierarchical\SupportTemplates\FormRequest;
use Mrzkit\TemplateEngine\TemplateEngine;
use Mrzkit\TemplateEngine\TemplateFileReader;
use Mrzkit\TemplateEngine\TemplateFileWriter;

class TemplateHandler
{
    public function shardRepositoryCreation(string $name) : array
    {
        $result = [];

        $templateContract = new ShardModel($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        /*
        $templateContract = new ShardModelEvent($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ShardModelListener($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];
        */

        $templateContract = new ShardModelRepository($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ShardModelRepositoryComplex($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ShardModelRepositoryFactory($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function shardControllerCreation(string $name) : array
    {
        $result = [];

        $templateContract = new ShardController($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function shardServiceCreation(string $name) : array
    {
        $result = [];

        $templateContract = new ShardServiceBusinessService($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ShardService($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ShardServiceFactory($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function requestCreation(string $name) : array
    {
        $result = [];

        $templateContract = new IndexRequest($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ManyRequest($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new StoreRequest($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new UpdateRequest($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function formRequestCreation(string $name) : array
    {
        $result = [];

        $templateContract = new FormRequest($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function repositoryCreation(string $name) : array
    {
        $result = [];

        $templateContract = new Model($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ModelRepository($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ModelRepositoryComplex($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ModelRepositoryFactory($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function controllerCreation(string $name) : array
    {
        $result = [];

        $templateContract = new Controller($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function serviceCreation(string $name) : array
    {
        $result = [];

        $templateContract = new BusinessService($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ServiceFactory($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new Service($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function componentCreation(string $name) : array
    {
        $result = [];

        $templateContract = new Component($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ComponentImpl($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ComponentInterface($name);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ComponentAbstract("ComponentAbstract");

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        $templateContract = new ComponentContract("ComponentContract");

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function routeCreation(string $name) : array
    {
        $result = [];

        $templateContract = new Route($name);

        $routePath = $templateContract->getRoutePath();

        $resultContent = $this->handleReplace($templateContract);

        $templateContract = new RouteReplace($name, $routePath, $resultContent);

        $result[] = [
            'result'       => $this->handle($templateContract),
            'saveFilename' => $templateContract->getSaveFilename(),
        ];

        return $result;
    }

    public function handleReplace(TemplateContract $templateContract) : string
    {
        // 读取文件
        $reader = new TemplateFileReader($templateContract->getSourceTemplateFile());
        // 实例化替换引擎
        $engine = new TemplateEngine($reader);
        // 初始化配置
        $engine->setContentReplacements($templateContract->getReplacementRules())->setContentReplacementsCallback($templateContract->getReplacementRuleCallbacks());
        // 执行替换
        $engine->replaceContentReplacements()->replaceContentReplacementsCallback();

        // 返回替换结果
        return $engine->getReplaceResult();
    }

    public function handle(TemplateContract $templateContract) : bool
    {
        // 读取文件
        $reader = new TemplateFileReader($templateContract->getSourceTemplateFile());
        // 实例化替换引擎
        $engine = new TemplateEngine($reader);
        // 初始化配置
        $engine->setContentReplacements($templateContract->getReplacementRules())->setContentReplacementsCallback($templateContract->getReplacementRuleCallbacks());
        // 执行替换
        $engine->replaceContentReplacements()->replaceContentReplacementsCallback();
        // 写入文件
        $writer = new TemplateFileWriter($templateContract->getSaveFilename());
        // 写入操作
        $result = $writer->setContent($engine->getReplaceResult())->setForce($templateContract->getForceCover())->saveFile();

        return $result;
    }
}

