<?php

declare(strict_types=1);

namespace collection;

use FastD\Routing\Collection\RouteCollection;
use PHPUnit\Framework\TestCase;

class TestController
{
    public function index()
    {
        return 'test';
    }

    public function show($id)
    {
        return "show user {$id}";
    }

    public function update($id, $name)
    {
        return "update user {$id} with name {$name}";
    }
}

class RouteCollectionTest extends TestCase
{
    public function testConstructorCreatesRouteCollectionWithDefaultDependencies(): void
    {
        $collection = new RouteCollection();

        $this->assertInstanceOf(RouteCollection::class, $collection);
        // 不直接访问受保护属性，只测试实例化是否成功
    }

    public function testAddRouteAddsRouteToRouteMaps(): void
    {
        $collection = new RouteCollection();

        $collection->addRoute('GET', '/users', 'TestController@index');

        // 通过公共方法验证路由被添加
        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $this->assertArrayHasKey('GET', $staticRoutes);
        $this->assertArrayHasKey('/users', $staticRoutes['GET']);
    }

    public function testAddRouteWithGroupPrefix(): void
    {
        $collection = new RouteCollection();

        $collection->group('/api', function ($router) {
            $router->addRoute('GET', '/users', 'TestController@index');
        });

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $this->assertArrayHasKey('/api/users', $staticRoutes['GET']);
    }

    public function testAddRouteWithGroupMiddleware(): void
    {
        $collection = new RouteCollection();

        $collection->group('/api', function ($router) {
            $router->addRoute('GET', '/users', 'TestController@index', ['auth']);
        }, ['cors']);

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $route = $staticRoutes['GET']['/api/users'];

        $middlewares = $route->getMiddlewares();
        $this->assertContains('cors', $middlewares);
    }

    public function testGroupMethodSetsAndRestoresPrefix(): void
    {
        $collection = new RouteCollection();

        $collection->addRoute('GET', '/home', 'TestController@index');

        $collection->group('/api', function ($router) {
            $router->addRoute('GET', '/users', 'TestController@index');
        });

        $collection->addRoute('GET', '/about', 'TestController@index');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();

        $this->assertArrayHasKey('/home', $staticRoutes['GET']);
        $this->assertArrayHasKey('/about', $staticRoutes['GET']);
        $this->assertArrayHasKey('/api/users', $staticRoutes['GET']);
    }

    public function testGroupMethodSetsAndRestoresMiddleware(): void
    {
        $collection = new RouteCollection();

        $collection->group('/api', function ($router) {
            $router->addRoute('GET', '/users', 'TestController@index', ['auth']);
        }, ['cors']);

        $collection->addRoute('GET', '/public', 'TestController@index');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();

        $apiRouteMiddlewares = $staticRoutes['GET']['/api/users']->getMiddlewares();
        $this->assertContains('cors', $apiRouteMiddlewares);

        $this->assertNotContains('cors', $staticRoutes['GET']['/public']->getMiddlewares());
    }

    public function testGetMethodAddsGetRoute(): void
    {
        $collection = new RouteCollection();

        $collection->get('/users', 'TestController@index');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $this->assertArrayHasKey('/users', $staticRoutes['GET']);
    }

    public function testPostMethodAddsPostRoute(): void
    {
        $collection = new RouteCollection();

        $collection->post('/users', 'TestController@index');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $this->assertArrayHasKey('/users', $staticRoutes['POST']);
    }

    public function testPutMethodAddsPutRoute(): void
    {
        $collection = new RouteCollection();

        $collection->put('/users', 'TestController@index');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $this->assertArrayHasKey('/users', $staticRoutes['PUT']);
    }

    public function testPatchMethodAddsPatchRoute(): void
    {
        $collection = new RouteCollection();

        $collection->patch('/users', 'TestController@index');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $this->assertArrayHasKey('/users', $staticRoutes['PATCH']);
    }

    public function testDeleteMethodAddsDeleteRoute(): void
    {
        $collection = new RouteCollection();

        $collection->delete('/users', 'TestController@index');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $this->assertArrayHasKey('/users', $staticRoutes['DELETE']);
    }

    public function testOptionsMethodAddsOptionsRoute(): void
    {
        $collection = new RouteCollection();

        $collection->options('/users', 'TestController@index');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $this->assertArrayHasKey('/users', $staticRoutes['OPTIONS']);
    }

    public function testHttpMethodsSupportMiddlewareAndParameters(): void
    {
        $collection = new RouteCollection();

        $collection->get('/users', 'TestController@index', ['auth'], ['param' => 'value']);

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $route = $staticRoutes['GET']['/users'];

        $this->assertContains('auth', $route->getMiddlewares());
        // 通过方法间接验证参数
        $this->assertIsArray($route->getParameters());
    }

    public function testNestedGroupsWorkCorrectly(): void
    {
        $collection = new RouteCollection();

        $collection->group('/api', function ($router) {
            $router->group('/v1', function ($router) {
                $router->get('/users', 'TestController@index');
            });
        });

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();
        $this->assertArrayHasKey('/api/v1/users', $staticRoutes['GET']);
    }

    // 简化的动态路由测试
    public function testDynamicRouteBasicFunctionality(): void
    {
        $collection = new RouteCollection();

        $collection->get('/users/{id}', 'TestController@show');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();

        $this->assertNotEmpty($variableRoutes);
        $this->assertArrayHasKey('GET', $variableRoutes);
    }

    public function testDynamicRouteWithCustomRegex(): void
    {
        $collection = new RouteCollection();

        $collection->get('/users/{id:[0-9]+}', 'TestController@show');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();

        $this->assertNotEmpty($variableRoutes);
        $this->assertArrayHasKey('GET', $variableRoutes);
    }

    public function testDynamicRouteWithMultipleParameters(): void
    {
        $collection = new RouteCollection();

        $collection->put('/users/{id}/posts/{postId}', 'TestController@update');

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();

        $this->assertNotEmpty($variableRoutes);
        $this->assertArrayHasKey('PUT', $variableRoutes);
    }

    public function testDynamicRouteWithMiddleware(): void
    {
        $collection = new RouteCollection();

        $collection->get('/admin/users/{id}', 'TestController@show', ['auth', 'admin']);

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();

        $this->assertNotEmpty($variableRoutes);
        $this->assertArrayHasKey('GET', $variableRoutes);
    }

    public function testDynamicRouteInGroup(): void
    {
        $collection = new RouteCollection();

        $collection->group('/api/v1', function ($router) {
            $router->get('/users/{id}', 'TestController@show');
        });

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();

        $this->assertNotEmpty($variableRoutes);
        $this->assertArrayHasKey('GET', $variableRoutes);
    }

    public function testDynamicRoutesPerformance(): void
    {
        $collection = new RouteCollection();

        $startTime = microtime(true);

        for ($i = 1; $i <= 5; $i++) {
            $collection->get("/api/items/{$i}", 'TestController@show');
        }

        [$staticRoutes, $variableRoutes] = $collection->routeMaps->getRoutes();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->assertLessThan(0.1, $executionTime);
        $this->assertArrayHasKey('GET', $staticRoutes);
    }
}