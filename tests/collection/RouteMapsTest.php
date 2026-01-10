<?php

declare(strict_types=1);

use FastD\Routing\Collection\RouteMaps;
use FastD\Routing\Collection\RouteParser;
use FastD\Routing\Exceptions\RouteException;
use PHPUnit\Framework\TestCase;

class RouteMapsTest extends TestCase
{
    private RouteMaps $routeMaps;
    private RouteParser $routeParser;

    protected function setUp(): void
    {
        $this->routeMaps = new RouteMaps();
        $this->routeParser = new RouteParser();
    }

    public function testAddStaticRoute(): void
    {
        $routeData = ['/users'];
        $this->routeMaps->addRoute('GET', $routeData, 'UserController@index', [], []);
        
        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();
        
        $this->assertArrayHasKey('GET', $staticRoutes);
        $this->assertArrayHasKey('/users', $staticRoutes['GET']);
        $this->assertEmpty($variableRoutes);
        
        $route = $staticRoutes['GET']['/users'];
        $this->assertInstanceOf('FastD\Routing\Collection\Route', $route);
    }

    public function testAddVariableRoute(): void
    {
        $routeData = ['/', ['id', '[0-9]+']];
        $this->routeMaps->addRoute('GET', $routeData, 'UserController@show', [], []);
        
        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();
        
        $this->assertEmpty($staticRoutes);
        $this->assertArrayHasKey('GET', $variableRoutes);
        
        $routesChunk = $variableRoutes['GET'][0];
        $this->assertArrayHasKey('regex', $routesChunk);
        $this->assertArrayHasKey('routeMap', $routesChunk);
        $this->assertStringContainsString('[0-9]+', $routesChunk['regex']);
    }

    public function testStaticRouteDuplicateDetection(): void
    {
        $routeData = ['/users'];
        $this->routeMaps->addRoute('GET', $routeData, 'UserController@index', [], []);

        $this->expectException(RouteException::class);
        $this->expectExceptionMessage('Cannot register two routes matching "/users" for method "GET"');

        $this->routeMaps->addRoute('GET', $routeData, 'UserController@show', [], []);
    }

    public function testVariableRouteDuplicateDetection(): void
    {
        $routeData1 = ['/', ['id', '[0-9]+']];
        $this->routeMaps->addRoute('GET', $routeData1, 'UserController@show', [], []);

        $this->expectException(RouteException::class);
        $this->expectExceptionMessage('Cannot register two routes matching');

        $routeData2 = ['/', ['id', '[0-9]+']];
        $this->routeMaps->addRoute('GET', $routeData2, 'PostController@show', [], []);
    }

    public function testDifferentMethodsSamePathAllowed(): void
    {
        $routeData = ['/users'];
        $this->routeMaps->addRoute('GET', $routeData, 'UserController@index', [], []);
        $this->routeMaps->addRoute('POST', $routeData, 'UserController@store', [], []);

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();

        $this->assertArrayHasKey('GET', $staticRoutes);
        $this->assertArrayHasKey('POST', $staticRoutes);
        $this->assertArrayHasKey('/users', $staticRoutes['GET']);
        $this->assertArrayHasKey('/users', $staticRoutes['POST']);
    }

    public function testRouteParametersAndMiddleware(): void
    {
        $parameters = ['version' => 'v1', 'auth' => true];
        $middleware = ['AuthMiddleware', 'CorsMiddleware'];

        $routeData = ['/api/protected'];
        $this->routeMaps->addRoute('GET', $routeData, 'ProtectedController@index', $middleware, $parameters);

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();
        $route = $staticRoutes['GET']['/api/protected'];

        $this->assertEquals($parameters, $route->getParameters()['definition']);
        $this->assertEquals($middleware, $route->getMiddlewares());
    }

    public function testMediumScaleStaticRoutesPerformance(): void
    {
        $startTime = microtime(true);
        $routeCount = 50;

        for ($i = 1; $i <= $routeCount; $i++) {
            $routeData = ["/api/resource{$i}"];
            $this->routeMaps->addRoute('GET', $routeData, "ResourceController@show{$i}", [], []);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();
        $this->assertCount($routeCount, $staticRoutes['GET']);

        $this->assertLessThan(1.0, $executionTime, "Adding {$routeCount} routes took too long: {$executionTime}s");

        $testPaths = ['/api/resource1', '/api/resource25', '/api/resource50'];
        foreach ($testPaths as $path) {
            $this->assertArrayHasKey($path, $staticRoutes['GET']);
        }
    }

    public function testLargeVariableRoutesHandling(): void
    {
        $startTime = microtime(true);
        $routeCount = 10;

        for ($i = 1; $i <= $routeCount; $i++) {
            $routeData = ["/api/items/", ["id{$i}", "[0-9]+"], "/details/{$i}"];
            $this->routeMaps->addRoute('GET', $routeData, "ItemController@show{$i}", [], []);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();
        $this->assertNotEmpty($variableRoutes['GET']);

        $this->assertLessThan(2.0, $executionTime, "Adding {$routeCount} variable routes took too long: {$executionTime}s");
        $this->assertGreaterThanOrEqual(1, count($variableRoutes['GET']));
    }

    public function testMixedRouteTypeSeparation(): void
    {
        $staticCount = 5;
        $variableCount = 3;

        for ($i = 1; $i <= $staticCount; $i++) {
            $routeData = ["/static/api{$i}/endpoint"];
            $this->routeMaps->addRoute('GET', $routeData, "StaticController@handle{$i}", [], []);
        }

        for ($i = 1; $i <= $variableCount; $i++) {
            $routeData = ["/variable/api/", ["param{$i}", "[a-z]+"], "/endpoint/{$i}"];
            $this->routeMaps->addRoute('GET', $routeData, "VariableController@process{$i}", [], []);
        }

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();

        $this->assertCount($staticCount, $staticRoutes['GET']);
        $this->assertNotEmpty($variableRoutes['GET']);

        $this->assertArrayHasKey('/static/api1/endpoint', $staticRoutes['GET']);
        $this->assertArrayHasKey('/static/api5/endpoint', $staticRoutes['GET']);
    }

    public function testRouteChunkingFunctionality(): void
    {
        $routeCount = 15;

        for ($i = 1; $i <= $routeCount; $i++) {
            $routeData = ["/api/data/", ["id{$i}", "[0-9]+"], "/item/{$i}"];
            $this->routeMaps->addRoute('GET', $routeData, "DataController@getItem{$i}", [], []);
        }

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();

        $this->assertGreaterThanOrEqual(1, count($variableRoutes['GET']));

        foreach ($variableRoutes['GET'] as $chunk) {
            $this->assertArrayHasKey('regex', $chunk);
            $this->assertArrayHasKey('routeMap', $chunk);
            $this->assertNotEmpty($chunk['routeMap']);
        }
    }

    public function testSimilarRoutesDistinction(): void
    {
        $routesToAdd = [
            [['/api/users'], 'UsersController@index'],
            [['/api/users/', ['id', '[0-9]+']], 'UsersController@show'],
            [['/api/user/', ['userId', '[0-9]+']], 'UserController@show'],
        ];

        foreach ($routesToAdd as [$routeData, $handler]) {
            $this->routeMaps->addRoute('GET', $routeData, $handler, [], []);
        }

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();

        $this->assertArrayHasKey('/api/users', $staticRoutes['GET']);
        $this->assertNotEmpty($variableRoutes['GET']);

        $staticRoute = $staticRoutes['GET']['/api/users'];
        $this->assertInstanceOf('FastD\Routing\Collection\Route', $staticRoute);
    }

    public function testSpecialCharacterPaths(): void
    {
        $specialCases = [
            [['/'], 'RootController@index'],
            [['/api/v1/users+data'], 'SpecialController@handle1'],
            [['/files/document.pdf'], 'SpecialController@handle2'],
        ];

        foreach ($specialCases as [$routeData, $handler]) {
            $this->routeMaps->addRoute('GET', $routeData, $handler, [], []);
        }

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();

        $this->assertArrayHasKey('/', $staticRoutes['GET']);
        $this->assertArrayHasKey('/api/v1/users+data', $staticRoutes['GET']);
        $this->assertArrayHasKey('/files/document.pdf', $staticRoutes['GET']);
    }

    public function testFullHttpMethodSupport(): void
    {
        $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
        $basePath = '/api/resource';

        foreach ($methods as $method) {
            $routeData = [$basePath];
            $this->routeMaps->addRoute($method, $routeData, "ResourceController@{$method}", [], []);
        }

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();

        foreach ($methods as $method) {
            $this->assertArrayHasKey($method, $staticRoutes);
            $this->assertArrayHasKey($basePath, $staticRoutes[$method]);
        }
    }

    public function testWildcardMethodSupport(): void
    {
        $routeData = ['/fallback'];
        $this->routeMaps->addRoute('*', $routeData, 'FallbackController@handle', [], []);

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();

        $this->assertArrayHasKey('*', $staticRoutes);
        $this->assertArrayHasKey('/fallback', $staticRoutes['*']);
    }

    public function testRouteWithEmptyPath(): void
    {
        $routeData = [''];
        $this->routeMaps->addRoute('GET', $routeData, 'HomeController@index', [], []);

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();

        $this->assertArrayHasKey('', $staticRoutes['GET']);
    }

    public function testRouteWithMultipleSegments(): void
    {
        $routeData = ['/admin/dashboard/reports/daily'];
        $this->routeMaps->addRoute('GET', $routeData, 'AdminController@showReport', [], []);

        [$staticRoutes, $variableRoutes] = $this->routeMaps->getRoutes();

        $this->assertArrayHasKey('/admin/dashboard/reports/daily', $staticRoutes['GET']);
    }
}