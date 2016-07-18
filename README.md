# FastD Routing

![Building](https://api.travis-ci.org/JanHuang/routing.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/fastd/routing/v/stable)](https://packagist.org/packages/fastd/routing) [![Total Downloads](https://poser.pugx.org/fastd/routing/downloads)](https://packagist.org/packages/fastd/routing) [![Latest Unstable Version](https://poser.pugx.org/fastd/routing/v/unstable)](https://packagist.org/packages/fastd/routing) [![License](https://poser.pugx.org/fastd/routing/license)](https://packagist.org/packages/fastd/routing)

简单的 PHP 路由器，支持路由嵌套，动态路由等。

## 要求

* PHP 7.0+

## Composer

```
composer require "fastd/routing:3.0.x-dev"
```

## 使用

可以通过 `Router` 对象设置路由，也可以通过路由列表创建路由.

### 通过路由器配置

```php
$router = new FastD\Routing\Router();
$router->addRoute('name', 'GET', '/', function () {
    return 'hello world';
});
$callback = $router->dispatch('GET', '/');
```

### 路由列表配置

```php
Routes::get('name', '/', function () {
    return 'hello world';
});

$callback = Routes::getRouter()->dispatch('GET', '/');
```

### 路由多个请求方法

```php
Routes::get('name_get', '/', function () {
    return 'hello world';
});

Routes::post('name_post', '/', function () {
    return 'hello world';
});

$callback = Routes::getRouter()->dispatch('POST', '/');
```

### 动态路由

```php
Routes::get('name', '/{name}', function () {
    return 'hello world';
});

$callback = Routes::getRouter()->dispatch('GET', '/janhuang');
```

**路由调度仅匹配并返回一个路由对象，具体回调操作需要通过 `Route::getCallback` 进行获取**

## Testing

```
cd path/to/routing
composer install
phpunit
```

## License MIT
