<?php

declare(strict_types=1);

use FastD\Routing\Matched;
use FastD\Routing\Collection\Route;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class MatchedTest extends TestCase
{
    public function testConstructorCreatesMatchedWithCorrectProperties(): void
    {
        $request = new ServerRequest('GET', '/users/123');
        $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id']);
        $vars = ['id' => '123'];
        
        $matched = new Matched($request, $route, $vars);
        
        $this->assertInstanceOf(Matched::class, $matched);
        // 由于 Matched 构造函数会修改请求，所以我们不能使用assertSame
        $this->assertEquals($request->getMethod(), $matched->getServerRequest()->getMethod());
        $this->assertEquals($request->getUri()->getPath(), $matched->getServerRequest()->getUri()->getPath());
        $this->assertSame($route, $matched->getRoute());
    }

    public function testConstructorSetsMatchedVariablesOnRoute(): void
    {
        $request = new ServerRequest('GET', '/users/123');
        $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id']);
        $vars = ['id' => '123'];
        
        $matched = new Matched($request, $route, $vars);
        
        // 验证路由参数已被设置
        $parameters = $route->getParameters();
        $this->assertArrayHasKey('matched', $parameters);
        $this->assertEquals(['id' => '123'], $parameters['matched']);
    }

    public function testConstructorSetsRequestAttributes(): void
    {
        $request = new ServerRequest('GET', '/users/123');
        $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id']);
        $vars = ['id' => '123'];
        
        $matched = new Matched($request, $route, $vars);
        $resultRequest = $matched->getServerRequest();
        
        // 验证请求属性已设置
        $this->assertEquals('123', $resultRequest->getAttribute('id'));
    }

    public function testConstructorMergesDefinitionAndMatchedVariables(): void
    {
        $request = new ServerRequest('GET', '/users/123');
        $definition = ['default_param' => 'default_value'];
        $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id'], [], $definition);
        $vars = ['id' => '123'];
        
        $matched = new Matched($request, $route, $vars);
        $resultRequest = $matched->getServerRequest();
        
        // 验证定义参数和匹配参数都被设置为请求属性
        $this->assertEquals('123', $resultRequest->getAttribute('id'));
        $this->assertEquals('default_value', $resultRequest->getAttribute('default_param'));
    }

    public function testGetRouteReturnsCorrectRoute(): void
    {
        $request = new ServerRequest('GET', '/users/123');
        $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id']);
        $vars = ['id' => '123'];
        
        $matched = new Matched($request, $route, $vars);
        
        $returnedRoute = $matched->getRoute();
        $this->assertSame($route, $returnedRoute);
        $this->assertEquals('GET', $returnedRoute->getMethod());
        $this->assertEquals('TestController@show', $returnedRoute->getHandler());
    }

    public function testGetServerRequestReturnsCorrectRequest(): void
    {
        $request = new ServerRequest('GET', '/users/123');
        $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id']);
        $vars = ['id' => '123'];
        
        $matched = new Matched($request, $route, $vars);
        
        $returnedRequest = $matched->getServerRequest();
        $this->assertInstanceOf(ServerRequestInterface::class, $returnedRequest);
        $this->assertEquals('GET', $returnedRequest->getMethod());
        $this->assertEquals('/users/123', $returnedRequest->getUri()->getPath());
    }

    public function testMultipleVariablesHandling(): void
    {
        $request = new ServerRequest('GET', '/users/123/posts/456');
        $route = new Route('GET', 'TestController@show', 'users/(\d+)/posts/(\d+)', ['userId', 'postId']);
        $vars = ['userId' => '123', 'postId' => '456'];
        
        $matched = new Matched($request, $route, $vars);
        $resultRequest = $matched->getServerRequest();
        
        $this->assertEquals('123', $resultRequest->getAttribute('userId'));
        $this->assertEquals('456', $resultRequest->getAttribute('postId'));
    }

    public function testEmptyVariablesHandling(): void
    {
        $request = new ServerRequest('GET', '/users');
        $route = new Route('GET', 'TestController@index', 'users', []);
        $vars = [];
        
        $matched = new Matched($request, $route, $vars);
        $resultRequest = $matched->getServerRequest();
        
        // 应该没有额外的属性被设置
        $attributes = $resultRequest->getAttributes();
        $this->assertCount(0, $attributes);
    }

    public function testVariableOverridingDefinitionParameters(): void
    {
        $request = new ServerRequest('GET', '/users/123');
        $definition = ['id' => 'default_id'];
        $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id'], [], $definition);
        $vars = ['id' => '123']; // 匹配的变量应该覆盖定义的默认值
        
        $matched = new Matched($request, $route, $vars);
        $resultRequest = $matched->getServerRequest();
        
        // 匹配的变量值应该优先生效
        $this->assertEquals('123', $resultRequest->getAttribute('id'));
    }

    public function testImmutableRequestBehavior(): void
    {
        $originalRequest = new ServerRequest('GET', '/users/123');
        $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id']);
        $vars = ['id' => '123'];
        
        $matched = new Matched($originalRequest, $route, $vars);
        $resultRequest = $matched->getServerRequest();
        
        // 验证原始请求没有被修改
        $this->assertNotSame($originalRequest, $resultRequest);
        $this->assertEquals('', $originalRequest->getAttribute('id', ''));
        $this->assertEquals('123', $resultRequest->getAttribute('id'));
    }

    public function testRouteParameterStructure(): void
    {
        $request = new ServerRequest('GET', '/users/123');
        $definition = ['version' => 'v1'];
        $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id'], [], $definition);
        $vars = ['id' => '123'];
        
        $matched = new Matched($request, $route, $vars);
        
        $parameters = $route->getParameters();
        
        // 验证参数结构
        $this->assertArrayHasKey('definition', $parameters);
        $this->assertArrayHasKey('matched', $parameters);
        $this->assertEquals(['version' => 'v1'], $parameters['definition']);
        $this->assertEquals(['id' => '123'], $parameters['matched']);
    }

    public function testPerformanceMultipleInstantiations(): void
    {
        $startTime = microtime(true);
        
        for ($i = 0; $i < 100; $i++) {
            $request = new ServerRequest('GET', "/users/{$i}");
            $route = new Route('GET', 'TestController@show', 'users/(\d+)', ['id']);
            $vars = ['id' => (string)$i];
            
            $matched = new Matched($request, $route, $vars);
            
            $this->assertInstanceOf(Matched::class, $matched);
            $this->assertEquals((string)$i, $matched->getServerRequest()->getAttribute('id'));
        }
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        $this->assertLessThan(0.1, $executionTime, "Multiple instantiations took too long: {$executionTime}s");
    }
}