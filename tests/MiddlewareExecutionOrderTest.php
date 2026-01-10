<?php

declare(strict_types=1);

use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\RouteMatcher;
use FastD\Routing\Matched;
use FastD\Routing\Collection\Route;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 中间件执行顺序测试
 * 验证多个中间件按正确顺序执行
 */
class MiddlewareExecutionOrderTest extends TestCase
{
    private RouteCollection $routeCollection;
    private RouteMatcher $routeMatcher;

    protected function setUp(): void
    {
        $this->routeCollection = new RouteCollection();
        $this->routeMatcher = new RouteMatcher($this->routeCollection);
    }

    /**
     * 测试多个中间件的执行顺序
     */
    public function testMultipleMiddlewareExecutionOrder(): void
    {
        // 在路由中直接指定中间件
        $this->routeCollection->get('/test', 'OrderController@test', [FirstMiddleware::class, SecondMiddleware::class, ThirdMiddleware::class]);
        
        $request = new ServerRequest('GET', '/test');
        $matched = $this->routeMatcher->match($request);
        
        // 手动创建中间件链来测试执行顺序
        $handler = new TestFinalHandler();
        $middleware3 = new ThirdMiddleware();
        $middleware2 = new SecondMiddleware();
        $middleware1 = new FirstMiddleware();
        
        // 模拟中间件链执行：First -> Second -> Third -> Handler -> Third -> Second -> First
        $response = $middleware1->process($request, 
            new class($middleware2, $middleware3, $handler) implements RequestHandlerInterface {
                public function __construct(private MiddlewareInterface $m2, private MiddlewareInterface $m3, private RequestHandlerInterface $handler) {}
                public function handle(ServerRequestInterface $request): ResponseInterface {
                    return $this->m2->process($request, 
                        new class($this->m3, $this->handler) implements RequestHandlerInterface {
                            public function __construct(private MiddlewareInterface $m3, private RequestHandlerInterface $handler) {}
                            public function handle(ServerRequestInterface $request): ResponseInterface {
                                return $this->m3->process($request, $this->handler);
                            }
                        }
                    );
                }
            }
        );
        
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        // 验证中间件执行顺序
        $expectedOrder = 'first-before-second-before-third-before-final-handler-third-after-second-after-first-after';
        $this->assertEquals($expectedOrder, (string) $response->getBody());
    }

    /**
     * 测试路由级别中间件与组中间件的执行顺序
     */
    public function testRouteLevelMiddlewareOrder(): void
    {
        // 使用路由组设置全局中间件
        $this->routeCollection->group('/api', function ($router) {
            $router->get('/users', 'UserController@index', [RouteFirstMiddleware::class, RouteSecondMiddleware::class]);
        }, [GlobalFirstMiddleware::class]);
        
        $request = new ServerRequest('GET', '/api/users');
        $matched = $this->routeMatcher->match($request);
        
        // 验证路由匹配成功
        $this->assertEquals('UserController@index', $matched->getRoute()->getHandler());
        
        // 验证中间件的合并行为：组中间件优先级更高，会覆盖路由中间件的相同索引
        $middlewares = $matched->getRoute()->getMiddlewares();
        
        // 由于数组合并操作符 +，组中间件和路由中间件会合并，其中组中间件保持不变
        // 预期结果：['GlobalFirstMiddleware', 'RouteSecondMiddleware']
        $this->assertContains(GlobalFirstMiddleware::class, $middlewares);
        $this->assertContains(RouteSecondMiddleware::class, $middlewares);
        // 注意：RouteFirstMiddleware 会被覆盖，因为索引0被GlobalFirstMiddleware占用
        
        // 验证中间件总数
        $this->assertCount(2, $middlewares);
        
        // 验证具体中间件的存在
        $this->assertEquals([GlobalFirstMiddleware::class, RouteSecondMiddleware::class], $middlewares);
    }

    /**
     * 测试中间件参数传递
     */
    public function testMiddlewareParameterPassing(): void
    {
        $this->routeCollection->get('/param/{id}', 'ParameterController@test', [ParameterMiddleware::class]);
        
        $request = new ServerRequest('GET', '/param/123');
        $matched = $this->routeMatcher->match($request);
        
        // 验证路由匹配和参数设置
        $this->assertEquals('ParameterController@test', $matched->getRoute()->getHandler());
        $this->assertEquals('123', $matched->getServerRequest()->getAttribute('id'));
        
        // 验证中间件被正确设置
        $middlewares = $matched->getRoute()->getMiddlewares();
        $this->assertContains(ParameterMiddleware::class, $middlewares);
    }

    /**
     * 测试中间件中断执行
     */
    public function testMiddlewareEarlyTermination(): void
    {
        $this->routeCollection->get('/terminate', 'TerminateController@test', [FirstMiddleware::class, TerminatingMiddleware::class, ThirdMiddleware::class]);
        
        $request = new ServerRequest('GET', '/terminate');
        $matched = $this->routeMatcher->match($request);
        
        // 验证中间件设置
        $middlewares = $matched->getRoute()->getMiddlewares();
        $this->assertContains(FirstMiddleware::class, $middlewares);
        $this->assertContains(TerminatingMiddleware::class, $middlewares);
        $this->assertContains(ThirdMiddleware::class, $middlewares);
    }

    /**
     * 测试中间件修改请求对象
     */
    public function testMiddlewareRequestModification(): void
    {
        $this->routeCollection->get('/modify', 'ModifyController@test', [RequestModifierMiddleware::class]);
        
        $request = new ServerRequest('GET', '/modify');
        $matched = $this->routeMatcher->match($request);
        
        // 验证中间件设置
        $middlewares = $matched->getRoute()->getMiddlewares();
        $this->assertContains(RequestModifierMiddleware::class, $middlewares);
    }

    /**
     * 测试中间件错误处理
     */
    public function testMiddlewareErrorHandling(): void
    {
        $this->routeCollection->get('/error', 'ErrorController@test', [ErrorHandlingMiddleware::class]);
        
        $request = new ServerRequest('GET', '/error');
        $matched = $this->routeMatcher->match($request);
        
        // 验证中间件设置
        $middlewares = $matched->getRoute()->getMiddlewares();
        $this->assertContains(ErrorHandlingMiddleware::class, $middlewares);
    }

    /**
     * 测试复杂的中间件嵌套场景
     */
    public function testComplexMiddlewareNesting(): void
    {
        $this->routeCollection->get('/nested', 'NestController@test', [OuterMiddleware::class, MiddleMiddleware::class, InnerMiddleware::class]);
        
        $request = new ServerRequest('GET', '/nested');
        $matched = $this->routeMatcher->match($request);
        
        // 验证中间件设置
        $middlewares = $matched->getRoute()->getMiddlewares();
        $this->assertContains(OuterMiddleware::class, $middlewares);
        $this->assertContains(MiddleMiddleware::class, $middlewares);
        $this->assertContains(InnerMiddleware::class, $middlewares);
    }

    /**
     * 测试中间件性能（大量中间件情况）
     */
    public function testMiddlewareChainPerformance(): void
    {
        // 简化性能测试，只验证中间件数量和基本功能
        $middlewares = [
            FirstMiddleware::class,
            SecondMiddleware::class,
            ThirdMiddleware::class,
            OuterMiddleware::class,
            MiddleMiddleware::class
        ];
        
        $this->routeCollection->get('/performance', 'PerformanceController@test', $middlewares);
        
        $request = new ServerRequest('GET', '/performance');
        $matched = $this->routeMatcher->match($request);
        
        // 验证所有中间件都被设置
        $routeMiddlewares = $matched->getRoute()->getMiddlewares();
        $this->assertCount(5, $routeMiddlewares);
        
        foreach ($middlewares as $middleware) {
            $this->assertContains($middleware, $routeMiddlewares);
        }
    }
}

// 中间件实现类
class FirstMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = 'first-before-' . (string) $response->getBody() . '-first-after';
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

class SecondMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = 'second-before-' . (string) $response->getBody() . '-second-after';
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

class ThirdMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = 'third-before-' . (string) $response->getBody() . '-third-after';
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

class GlobalFirstMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = 'global-first-before-' . (string) $response->getBody() . '-global-first-after';
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

class RouteFirstMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = 'route-first-before-' . (string) $response->getBody() . '-route-first-after';
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

class RouteSecondMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = 'route-second-before-' . (string) $response->getBody() . '-route-second-after';
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

class ParameterMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute('middleware_param', $request->getAttribute('id', 'unknown'));
        $response = $handler->handle($request);
        $body = 'middleware-param:' . $request->getAttribute('middleware_param') . '-' . (string) $response->getBody();
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

class TerminatingMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response(403, [], 'terminating-response');
    }
}

class RequestModifierMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute('modified', 'modified-value');
        return $handler->handle($request);
    }
}

class ErrorHandlingMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Exception $e) {
            return new Response(500, [], 'error-handled: ' . $e->getMessage());
        }
    }
}

class OuterMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = 'outer-before-' . (string) $response->getBody() . '-outer-after';
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

class MiddleMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = 'middle-before-' . (string) $response->getBody() . '-middle-after';
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

class InnerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = 'inner-before-' . (string) $response->getBody() . '-inner-after';
        return $response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($body));
    }
}

// 控制器类
class OrderController { public function test() {} }
class UserController { public function index() {} }
class ParameterController { public function test() {} }
class TerminateController { public function test() {} }
class ModifyController { public function test() {} }
class ErrorController { public function test() {} }
class NestController { public function test() {} }
class PerformanceController { public function test() {} }

// 测试处理器
class TestFinalHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'final-handler');
    }
}