# FastD Routing

![Building](https://api.travis-ci.org/JanHuang/routing.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/fastd/routing/v/stable)](https://packagist.org/packages/fastd/routing) 
[![Total Downloads](https://poser.pugx.org/fastd/routing/downloads)](https://packagist.org/packages/fastd/routing) 
[![Latest Unstable Version](https://poser.pugx.org/fastd/routing/v/unstable)](https://packagist.org/packages/fastd/routing) 
[![License](https://poser.pugx.org/fastd/routing/license)](https://packagist.org/packages/fastd/routing)

简单的 PHP 路由器，支持路由嵌套，动态路由，模糊路由，中间件等。依赖于 [Http](https://github.com/JanHuang/http) 组件。

## 要求

* PHP 5.6+

## Composer

```
composer require "fastd/routing"
```

## 使用

可以通过 `RouteCollection` 对象设置路由，也可以通过路由列表创建路由. 详细文档: [fastd/routing](docs/zh_CN/readme.md)

### 静态路由

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->addRoute('GET', '/', function () {
    return 'hello world';
});

$route = $collection->match(new ServerRequest('GET', '/')); // \FastD\Routing\Route

echo call_user_func_array($route->getCallback(), []);
```

路由匹配并不会将路由的回调进行调用，但会返回整个 Route，方便回调处理，因此 `match` 仅返回匹配成功的路由对象

### 动态路由

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->addRoute('GET', '/{name}', function ($name) {
    return 'hello ' . $name;
});

$route = $collection->match(new ServerRequest('GET', '/foo')); // \FastD\Routing\Route

echo call_user_func_array($route->getCallback(), $route->getParameters());
```

在动态路由下，成功匹配的路由会将匹配成功的参数更新到 `getParameters` 中，通过 `getParameters` 获取成功匹配的参数信息。

### 同个路由, 多个方法

```php
$collection = new FastD\Routing\RouteCollection();
$collection->get('/', function () {
    return 'hello GET';
});
$collection->post('/', function () {
    return 'hello POST';
});
$response = $collection->dispatch('GET', '/'); // hello GET
$response = $collection->dispatch('POST', '/'); // hello POST
```

### 混合路由

在很多情况下，我们路由可能只差一个参数，以下做个例子。

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->addRoute('GET', '/{name}', function () {
    return 'get1';
});

$collection->addRoute('GET', '/', function () {
    return 'get2';
});

$route = $collection->match(new ServerRequest('GET', '/abc')); // \FastD\Routing\Route
$route2 = $collection->match(new ServerRequest('GET', '/')); // \FastD\Routing\Route
echo call_user_func_array($route->getCallback(), $route->getParameters());
echo call_user_func_array($route2->getCallback(), $route2->getParameters());
```

### 路由组

路由组会在你每个子路由钱添加自己的路由前缀，支持多层嵌套。

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->group('/v1', function (RouteCollection $collection) {
    $collection->addRoute('GET', '/{name}', function () {
        return 'get1';
    });
});

$route = $collection->match(new ServerRequest('GET', '/v1/abc'));

echo call_user_func_array($route->getCallback(), $route->getParameters());
```

### 模糊路由

模糊路由的灵感来自于 Swoole http server 的 onRequest 回调中，因为每个路由入口都经过 onRequest，那么自己造的时候，可能会有一些根据 pathinfo 进行处理的特殊路由，那么此时模糊路由就可以派上用场了。

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->addRoute('GET', '/api/*', function ($path) {
    return $path;
});

$route = $collection->match(new ServerRequest('GET', '/api/abc'));
echo call_user_func_array($route->getCallback(), $route->getParameters()); // /abc

$route = $collection->match(new ServerRequest('GET', '/api/cba'));
echo call_user_func_array($route->getCallback(), $route->getParameters()); // /cba
```

匹配凡是以 `/api` 开头的所有合法路由，然后进行回调

### 路由中间件

路由组件实现了路由中间件，基于 [Http](https://github.com/JanHuang/http) 和 [HTTP Middlewares](https://github.com/JanHuang/middleware) 实现。

> 路由中间件回调会自动回调 `Psr\Http\Message\ServerRequestInterface` 和 `FastD\Middleware\DelegateInterface` 两个对象作为参数。

中间件调用完成后，会返回 `\Psr\Http\Message\ResponseInterface` 对象，用于程序最终处理输出。

```php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->addRoute('GET', '/api/*', function (ServerRequest $request) {
    return 'hello';
});

$dispatcher = new \FastD\Routing\RouteDispatcher($collection);

$response = $dispatcher->dispatch(new ServerRequest('GET', '/api/abc'));

echo $response->getBody();
```

## Testing

```
phpunit
```

## License MIT
