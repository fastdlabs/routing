# FastD Routing

[![Build Status](https://travis-ci.org/fastdlabs/routing.svg?branch=master)](https://travis-ci.org/fastdlabs/routing)
[![Latest Stable Version](https://poser.pugx.org/fastd/routing/v/stable)](https://packagist.org/packages/fastd/routing)
[![Total Downloads](https://poser.pugx.org/fastd/routing/downloads)](https://packagist.org/packages/fastd/routing)
[![Latest Unstable Version](https://poser.pugx.org/fastd/routing/v/unstable)](https://packagist.org/packages/fastd/routing)
[![License](https://poser.pugx.org/fastd/routing/license)](https://packagist.org/packages/fastd/routing)

Simple PHP router that supports routing nesting, dynamic routing, fuzzy routing, middleware, and more. Relies on the [http](https://github.com/JanHuang/http) component.

## Claim

* PHP 7.2

## Composer

```
Composer require "fastd/routing"
```

## Use

You can set the route through the `RouteCollection` object, or you can create a route through the route list. Detailed documentation: [fastd/routing](docs/zh_CN/readme.md)

### Static routing

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

Route matching does not call the callback of the route, but returns the entire Route for callback processing, so `match` only returns the matching route object.

### Dynamic routing

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

Under dynamic routing, the successfully matched route will update the matching parameters to `getParameters`, and get the matching parameter information through `getParameters`.

### Same route, multiple methods

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

### Hybrid routing

In many cases, our route may only be one parameter difference. Here is an example.

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

### Routing Group

The routing group will add its own routing prefix to each of your sub-routing dollars, supporting multiple levels of nesting.

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

### Fuzzy routing

The inspiration for fuzzy routing comes from the onRequest callback of the Swoole http server. Because each route entry passes onRequest, when it is created, there may be some special routes processed according to pathinfo. Then the fuzzy route can be sent. It’s time to use.

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

Match all legal routes that start with `/api` and then callback

### Routing middleware

The routing component implements routing middleware based on [Http] (https://github.com/JanHuang/http) and [HTTP Middlewares] (https://github.com/JanHuang/middleware).

> Routing middleware callbacks automatically call back `Psr\Http\Message\ServerRequestInterface` and `FastD\Middleware\DelegateInterface` as arguments.

After the middleware call is completed, the `\Psr\Http\Message\ResponseInterface` object is returned for the program to process the output.

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

### Contribution

I am very pleased to be interested and willing to participate in the creation of a better PHP ecosystem, the developer of the Swoole Eco.

If you are happy with this, but don't know how to get started, you can try the following things:

* Problems encountered in your system [Feedback] (https://github.com/JanHuang/fastD/issues).
* Have better suggestions? Feel free to contact [bboyjanhuang@gmail.com] (mailto:bboyjanhuang@gmail.com) or [Sina Weibo: Coding Man] (http://weibo.com/ecbboyjan).

### Contact

If you encounter problems during use, please contact: [bboyjanhuang@gmail.com](mailto:bboyjanhuang@gmail.com). Weibo: [Coding Man] (http://weibo.com/ecbboyjan)

## License MIT
