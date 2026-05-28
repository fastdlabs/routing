<?php

declare(strict_types=1);

use FastD\Routing\Middleware\RouteMiddleware;
use FastD\Routing\Matched;
use FastD\Routing\Collection\Route;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteMiddlewareTest extends TestCase
{
    public function testConstructorAcceptsMatchedInterface(): void
    {
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', 'TestController@index', '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        
        $this->assertInstanceOf(RouteMiddleware::class, $middleware);
        $this->assertInstanceOf(MiddlewareInterface::class, $middleware);
    }

    public function testProcessWithControllerAtMethodHandler(): void
    {
        // 测试 RouteMiddleware 的实际行为 - 变量通过请求对象传递
        $request = new ServerRequest('GET', '/users/123');
        $request = $request->withAttribute('id', '123');
        $route = new Route('GET', 'TestRouteController@show', '', ['id']);
        $matched = new Matched($request, $route, ['id' => '123']);
        
        $middleware = new RouteMiddleware($matched);
        $handler = new TestNextHandler();
        
        // 路由变量通过请求对象的属性传递
        $response = $middleware->process($request, $handler);
        
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        // 变量通过请求属性正确传递
        $this->assertEquals('show user 123', (string) $response->getBody());
    }

    public function testProcessWithMiddlewareClassHandler(): void
    {
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', TestHandlerMiddleware::class, '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        $handler = new TestNextHandler();
        
        $response = $middleware->process($request, $handler);
        
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('middleware processed', (string) $response->getBody());
    }

    public function testProcessWithFunctionHandler(): void
    {
        // 定义测试函数
        if (!function_exists('test_route_function')) {
            function test_route_function(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
                return new Response(200, [], 'function response');
            }
        }
        
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', 'test_route_function', '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        $handler = new TestNextHandler();
        
        $response = $middleware->process($request, $handler);
        
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('function response', (string) $response->getBody());
    }

    public function testProcessWithCallableHandler(): void
    {
        $callable = function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
            return new Response(200, [], 'callable response');
        };
        
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', $callable, '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        $handler = new TestNextHandler();
        
        $response = $middleware->process($request, $handler);
        
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('callable response', (string) $response->getBody());
    }

    public function testProcessPassesRequestAttributesToController(): void
    {
        // 测试 RouteMiddleware 通过请求对象传递属性的行为
        $request = new ServerRequest('GET', '/users/456/comments/789');
        $request = $request->withAttribute('userId', '456');
        $request = $request->withAttribute('commentId', '789');
        $route = new Route('GET', 'TestRouteController@detail', '', ['userId', 'commentId']);
        $matched = new Matched($request, $route, ['userId' => '456', 'commentId' => '789']);
        
        $middleware = new RouteMiddleware($matched);
        $handler = new TestNextHandler();
        
        // 路由变量通过请求对象属性传递
        $response = $middleware->process($request, $handler);
        
        $this->assertInstanceOf(ResponseInterface::class, $response);
        // 变量通过请求属性正确传递
        $this->assertEquals('user 456 comment 789', (string) $response->getBody());
    }

    public function testProcessWithNoAdditionalParametersForMiddlewareType(): void
    {
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', TestHandlerMiddleware::class, '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        $handler = new TestNextHandler();
        
        // Middleware 类型的处理器只接收 $request 和 $handler 参数
        $response = $middleware->process($request, $handler);
        
        $this->assertEquals('middleware processed', (string) $response->getBody());
    }

    public function testProcessWithAdditionalParametersForNonMiddlewareTypes(): void
    {
        $request = new ServerRequest('GET', '/test');
        $request = $request->withAttribute('param1', 'value1');
        $request = $request->withAttribute('param2', 'value2');
        $route = new Route('GET', 'TestRouteController@index', '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        $handler = new TestNextHandler();
        
        // 非 middleware 类型会接收到额外的请求属性参数
        $response = $middleware->process($request, $handler);
        
        $this->assertEquals('controller response with params', (string) $response->getBody());
    }

    public function testFormattedRouteHandlerWithStringController(): void
    {
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', 'TestRouteController@index', '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        
        // 通过反射测试私有方法
        $reflection = new ReflectionClass($middleware);
        $method = $reflection->getMethod('formattedRouteHandler');
        $method->setAccessible(true);
        
        $result = $method->invoke($middleware, 'TestRouteController@index');
        
        $this->assertIsArray($result);
        $this->assertEquals('class', $result['type']);
        $this->assertIsArray($result['handler']);
        $this->assertCount(2, $result['handler']);
        $this->assertInstanceOf(TestRouteController::class, $result['handler'][0]);
        $this->assertEquals('index', $result['handler'][1]);
    }

    public function testFormattedRouteHandlerWithMiddlewareClass(): void
    {
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', TestHandlerMiddleware::class, '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        
        $reflection = new ReflectionClass($middleware);
        $method = $reflection->getMethod('formattedRouteHandler');
        $method->setAccessible(true);
        
        $result = $method->invoke($middleware, TestHandlerMiddleware::class);
        
        $this->assertEquals('middleware', $result['type']);
        $this->assertIsArray($result['handler']);
        $this->assertCount(2, $result['handler']);
        $this->assertInstanceOf(TestHandlerMiddleware::class, $result['handler'][0]);
        $this->assertEquals('process', $result['handler'][1]);
    }

    public function testFormattedRouteHandlerWithFunction(): void
    {
        if (!function_exists('test_function_handler')) {
            function test_function_handler() {
                return 'function result';
            }
        }
        
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', 'test_function_handler', '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        
        $reflection = new ReflectionClass($middleware);
        $method = $reflection->getMethod('formattedRouteHandler');
        $method->setAccessible(true);
        
        $result = $method->invoke($middleware, 'test_function_handler');
        
        $this->assertEquals('function', $result['type']);
        $this->assertEquals('test_function_handler', $result['handler']);
    }

    public function testFormattedRouteHandlerWithCallable(): void
    {
        $callable = function () {
            return 'callable result';
        };
        
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', $callable, '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        
        $reflection = new ReflectionClass($middleware);
        $method = $reflection->getMethod('formattedRouteHandler');
        $method->setAccessible(true);
        
        $result = $method->invoke($middleware, $callable);
        
        $this->assertEquals('callable', $result['type']);
        $this->assertSame($callable, $result['handler']);
    }

    public function testFormattedRouteHandlerThrowsExceptionForInvalidHandler(): void
    {
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', 'NonExistentController@method', '', []);
        $matched = new Matched($request, $route, []);
        
        $middleware = new RouteMiddleware($matched);
        
        $reflection = new ReflectionClass($middleware);
        $method = $reflection->getMethod('formattedRouteHandler');
        $method->setAccessible(true);
        
        $this->expectException(\FastD\Routing\Exceptions\RouteCallbackException::class);
        
        $method->invoke($middleware, 'NonExistentController@method');
    }

    public function testPerformanceMultipleProcessCalls(): void
    {
        $request = new ServerRequest('GET', '/test');
        $route = new Route('GET', 'TestRouteController@index', '', []);
        $matched = new Matched($request, $route, []);
        $middleware = new RouteMiddleware($matched);
        $handler = new TestNextHandler();
        
        $startTime = microtime(true);
        
        for ($i = 0; $i < 50; $i++) {
            $response = $middleware->process($request, $handler);
            $this->assertInstanceOf(ResponseInterface::class, $response);
        }
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        $this->assertLessThan(0.5, $executionTime, "Multiple process calls took too long: {$executionTime}s");
    }
}

// 测试控制器
class TestRouteController
{
    public function index(ServerRequestInterface $request, RequestHandlerInterface $handler, ...$params): ResponseInterface
    {
        return new Response(200, [], 'controller response with params');
    }
    
    public function show(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 处理可能缺失的 id 参数
        $id = $request->getAttribute('id', 'unknown');
        return new Response(200, [], "show user {$id}");
    }
    
    public function detail(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 处理可能缺失的参数
        $userId = $request->getAttribute('userId', 'unknown');
        $commentId = $request->getAttribute('commentId', 'unknown');
        return new Response(200, [], "user {$userId} comment {$commentId}");
    }
}

// 测试中间件处理器
class TestHandlerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response(200, [], 'middleware processed');
    }
}

// 测试下一个处理器
class TestNextHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'next handler response');
    }
}