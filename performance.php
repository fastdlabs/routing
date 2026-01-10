<?php
/**
 * FastD 路由性能对比测试工具
 * 用于检测不同路由数量下的性能表现
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Text as Response;
use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\RouteMatcher as RouteDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

include __DIR__ . '/vendor/autoload.php';

class DemoHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response(200, 'hello from demo handler');
    }
}

class TestController
{
    public function index(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        return new Response(200, 'hello from controller@index');
    }

    public function show(ServerRequestInterface $request, RequestHandlerInterface $handler, $id)
    {
        return new Response(200, "hello from controller@show id: $id");
    }
}

function testFunction(ServerRequestInterface $request, RequestHandlerInterface $handler, $param)
{
    return new Response(200, "hello from function param: $param");
}

// 性能测试主函数
function runBenchmark($testName, $callback) {
    $iterations = 10;
    $times = [];

    for ($i = 0; $i < $iterations; $i++) {
        $start = microtime(true);
        $callback();
        $end = microtime(true);
        $times[] = ($end - $start) * 1000; // 转换为毫秒
    }

    $avgTime = array_sum($times) / count($times);
    $minTime = min($times);
    $maxTime = max($times);
    
    printf("%-35s | Avg: %8.4fms | Min: %8.4fms | Max: %8.4fms\n", $testName, $avgTime, $minTime, $maxTime);
}

// 测试不同路由数量的性能
$routeCounts = [1000, 5000, 10000, 50000, 100000];

echo "\n=== FastD 路由注册性能对比测试 ===\n\n";
printf("%-10s | %-30s\n", "路由数量", "平均注册时间(ms)");
echo str_repeat("-", 50) . "\n";

foreach ($routeCounts as $count) {
    $startTime = microtime(true);
    
    $routeCollection = new RouteCollection();
    for ($i = 0; $i < $count; $i++) {
        $routeCollection->addRoute('GET', '/route' . $i . '/{param}', 'DemoHandler');
    }
    
    $endTime = microtime(true);
    $registrationTime = ($endTime - $startTime) * 1000;
    
    printf("%-10d | %8.4f\n", $count, $registrationTime);
}

echo "\n=== FastD 路由匹配性能对比测试 ===\n\n";

// 测试不同数量的路由匹配性能
echo "静态路由匹配性能对比:\n";
printf("%-10s | %-35s\n", "路由数量", "平均匹配时间(ms)");
echo str_repeat("-", 50) . "\n";

foreach ([1000, 5000, 10000, 50000, 100000] as $count) {
    $routeCollection = new RouteCollection();
    for ($i = 0; $i < $count; $i++) {
        $routeCollection->addRoute('GET', '/static' . $i, 'DemoHandler');
    }
    $routeMatcher = new RouteDispatcher($routeCollection);
    $request = new ServerRequest('GET', '/static' . ($count / 2)); // 匹配中间的路由
    
    $iterations = 100;
    $start = microtime(true);
    for ($i = 0; $i < $iterations; $i++) {
        $response = $routeMatcher->dispatch($request);
    }
    $end = microtime(true);
    $matchTime = (($end - $start) / $iterations) * 1000;
    
    printf("%-10d | %8.4f\n", $count, $matchTime);
}

echo "\n动态路由匹配性能测试 (单个动态路由):
"; 
$dynamicRouteCollection = new RouteCollection();
$dynamicRouteCollection->addRoute('GET', '/dynamic/{id}', 'DemoHandler');
$dynamicMatcher = new RouteDispatcher($dynamicRouteCollection);
$dynamicRequest = new ServerRequest('GET', '/dynamic/12345');

runBenchmark('Dynamic Route Match (100000 routes)', function() use ($dynamicMatcher, $dynamicRequest) {
    $response = $dynamicMatcher->dispatch($dynamicRequest);
});

echo "\n=== 不同处理器类型性能测试 (100000路由环境) ===\n";

// 在10000路由环境下测试不同处理器类型
$routeCollection = new RouteCollection();
for ($i = 0; $i < 10000; $i++) {
    $routeCollection->addRoute('GET', '/env' . $i . '/{param}', 'DemoHandler');
}

// 控制器@方法处理器
$routeCollectionCtrl = new RouteCollection();
$routeCollectionCtrl->get('/controller/{id}', 'TestController@show');
$ctrlMatcher = new RouteDispatcher($routeCollectionCtrl);
$ctrlRequest = new ServerRequest('GET', '/controller/999');

runBenchmark('Controller Handler', function() use ($ctrlMatcher, $ctrlRequest) {
    $response = $ctrlMatcher->dispatch($ctrlRequest);
});

// 中间件处理器
$routeCollectionMid = new RouteCollection();
$routeCollectionMid->get('/middleware', 'DemoHandler');
$midMatcher = new RouteDispatcher($routeCollectionMid);
$midRequest = new ServerRequest('GET', '/middleware');

runBenchmark('Middleware Handler', function() use ($midMatcher, $midRequest) {
    $response = $midMatcher->dispatch($midRequest);
});

// 函数处理器
$routeCollectionFunc = new RouteCollection();
$routeCollectionFunc->get('/function/{param}', 'testFunction');
$funcMatcher = new RouteDispatcher($routeCollectionFunc);
$funcRequest = new ServerRequest('GET', '/function/value');

runBenchmark('Function Handler', function() use ($funcMatcher, $funcRequest) {
    $response = $funcMatcher->dispatch($funcRequest);
});

echo "\n=== 性能测试完成 ===\n";