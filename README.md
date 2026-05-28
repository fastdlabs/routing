# FastD Routing

[![Build Status](https://travis-ci.org/fastdlabs/routing.svg?branch=master)](https://travis-ci.org/fastdlabs/routing)
[![Latest Stable Version](https://poser.pugx.org/fastd/routing/v/stable)](https://packagist.org/packages/fastd/routing)
[![Total Downloads](https://poser.pugx.org/fastd/routing/downloads)](https://packagist.org/packages/fastd/routing)
[![Latest Unstable Version](https://poser.pugx.org/fastd/routing/v/unstable)](https://packagist.org/packages/fastd/routing)
[![License](https://poser.pugx.org/fastd/routing/license)](https://packagist.org/packages/fastd/routing)

FastD Routing 是一个简单而强大的 PHP 路由器，支持路由嵌套、动态路由、模糊路由、中间件等功能。它依赖于 [Http](https://github.com/JanHuang/http) 组件，遵循 PSR-7 和 PSR-15 标准。

## 环境依赖

* PHP 8.2+
* [fastd/http](https://github.com/JanHuang/http)
* [psr/http-message](https://github.com/php-fig/http-message)
* [psr/http-server-handler](https://github.com/php-fig/http-server-handler)
* [psr/http-server-middleware](https://github.com/php-fig/http-server-middleware)

## 安装

通过 Composer 安装:

```
Composer require "fastd/routing"
```

## 基础使用

### Static routing

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use Zend\Diactoros\Response;

$collection = new RouteCollection();

$collection->addRoute('GET', '/', function (ServerRequest $request, $handler) {
    return new Response(200, [], 'hello world');
});

$route = $collection->match(new ServerRequest('GET', '/')); // \FastD\Routing\Route

// 执行路由回调
$response = call_user_func_array($route->getCallback(), [$request, $handler]);
echo $response->getBody();
```

### Dynamic routing

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use Zend\Diactoros\Response;

$collection = new RouteCollection();

$collection->addRoute('GET', '/user/{name}', function (ServerRequest $request, $handler) {
    $name = $request->getAttribute('name');
    return new Response(200, [], 'hello ' . $name);
});

$route = $collection->match(new ServerRequest('GET', '/user/john'));

// 获取匹配的参数
$params = $route->getParameters(); // ['name' => 'john']
```

### 路由约束

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use Zend\Diactoros\Response;

$collection = new RouteCollection();

// 数字参数约束
$collection->addRoute('GET', '/user/{id:[0-9]+}', function (ServerRequest $request, $handler) {
    $id = $request->getAttribute('id');
    return new Response(200, [], 'user id: ' . $id);
});

// 字母参数约束
$collection->addRoute('GET', '/profile/{name:[a-zA-Z]+}', function (ServerRequest $request, $handler) {
    $name = $request->getAttribute('name');
    return new Response(200, [], 'profile name: ' . $name);
});
```

### 路由组

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use Zend\Diactoros\Response;

$collection = new RouteCollection();

$collection->group('/api/v1', function (RouteCollection $collection) {
    $collection->addRoute('GET', '/users', function (ServerRequest $request, $handler) {
        return new Response(200, [], 'api v1 users');
    });
    
    $collection->addRoute('GET', '/posts', function (ServerRequest $request, $handler) {
        return new Response(200, [], 'api v1 posts');
    });
});

$route = $collection->match(new ServerRequest('GET', '/api/v1/users'));
```

### 路由中间件

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

// 自定义中间件
class ExampleMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 在处理前执行的操作
        $request = $request->withAttribute('timestamp', time());
        
        // 继续执行下一个中间件或路由处理器
        $response = $handler->handle($request);
        
        // 在处理后执行的操作
        return $response->withHeader('X-Middleware', 'processed');
    }
}

$collection = new RouteCollection();

$collection->addRoute('GET', '/middleware', function (ServerRequest $request, $handler) {
    return new Response(200, [], 'middleware example');
}, [
    ExampleMiddleware::class
]);

$dispatcher = new RouteDispatcher($collection);

$response = $dispatcher->dispatch(new ServerRequest('GET', '/middleware'));
echo $response->getBody();
```

### 多层中间件示例

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

// 定义多个中间件类
abstract class BaseNumberMiddleware implements MiddlewareInterface
{
    abstract protected function getMiddlewareName(): string;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $number = $request->getAttribute('number') ?? 0;
        $number += 1; // 每个中间件将数字加1
        
        $processedHistory = $request->getAttribute('processed_history', []);
        $processedHistory[] = [
            'middleware' => $this->getMiddlewareName(),
            'value' => $number
        ];
        
        $request = $request->withAttribute('number', $number)
                           ->withAttribute('processed_history', $processedHistory);
        
        return $handler->handle($request);
    }
}

class NumberIncrementMiddleware1 extends BaseNumberMiddleware
{
    protected function getMiddlewareName(): string
    {
        return self::class;
    }
}

class NumberIncrementMiddleware2 extends BaseNumberMiddleware
{
    protected function getMiddlewareName(): string
    {
        return self::class;
    }
}

class NumberIncrementMiddleware3 extends BaseNumberMiddleware
{
    protected function getMiddlewareName(): string
    {
        return self::class;
    }
}

$collection = new RouteCollection();

$collection->addRoute('GET', '/process/{number:[0-9]+}', function (ServerRequest $request, $handler) {
    $finalValue = $request->getAttribute('number');
    $processedHistory = $request->getAttribute('processed_history', []);
    
    $data = [
        'original_number' => $request->getAttribute('number') - count($processedHistory),
        'final_number' => (int)$finalValue,
        'processed_by' => $processedHistory,
        'message' => 'Processing completed successfully'
    ];
    
    $json = json_encode($data, JSON_PRETTY_PRINT);
    return new Response(200, ['Content-Type' => 'application/json'], $json);
}, [
    NumberIncrementMiddleware1::class,
    NumberIncrementMiddleware2::class,
    NumberIncrementMiddleware3::class
]);

$dispatcher = new RouteDispatcher($collection);
$response = $dispatcher->dispatch(new ServerRequest('GET', '/process/5'));
echo $response->getBody();
// 输出: 原始数字5，经过3个中间件处理后变成8，同时显示每个中间件的处理过程
```

## 文档

更多详细文档请参阅:

* [官方文档](https://github.com/JanHuang/fastD/wiki)
* [API 参考](https://github.com/JanHuang/routing/tree/master/src)
* [示例代码](https://github.com/JanHuang/routing/blob/master/example.php)

## 测试

运行单元测试:

```
phpunit
```

## 贡献

欢迎提交 Issue 和 Pull Request 来帮助改进此项目！

## License

MIT License