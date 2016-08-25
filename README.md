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

可以通过 `RouteCollection` 对象设置路由，也可以通过路由列表创建路由.

### 静态路由

```php
$collection = new FastD\Routing\RouteCollection();
$collection->addRoute('name', 'GET', '/', function () {
    return 'hello world';
});
$response = $collection->dispatch('GET', '/'); // hello world
```

### 动态路由

```php
$collection = new FastD\Routing\RouteCollection();
$collection->addRoute('name', 'GET', '/{name}', function ($name) {
    return 'hello ' . $name;
});
$response = $collection->dispatch('GET', '/janhuang'); // hello janhuang
```

动态路由会将变量数据注入到回调处理方法中。

##### 动态路由可选参数

可选参数通过 `[]` 中括号来表示。 配合路由第五个参数, 进行默认参数注入

```php
$collection = new FastD\Routing\RouteCollection();
$collection->addRoute('name', 'GET', '/[{name}]', function ($name) {
    return 'hello ' . $name;
}, ['name' => 'jan']);
$response = $collection->dispatch('GET', '/'); // hello jan
```

**注意,因为使用可选参数,必须设置默认值,否则路由会因为找不到默认值而出现异常**

### 同个路由, 多个方法

```php
$collection = new FastD\Routing\RouteCollection();
$collection->addRoute('name.get', 'GET', '/', function () {
    return 'hello GET';
});
$collection->addRoute('name.post', 'POST', '/', function () {
    return 'hello POST';
});
$response = $collection->dispatch('GET', '/'); // hello GET
$response = $collection->dispatch('POST', '/'); // hello POST
```

### 路由组

```php
$collection = new RouteCollection();

$collection->group('/user', function (RouteCollection $collection) {
    $collection->addRoute('user.profile', 'GET', '/profile/{name}', function ($name) {
        return 'hello ' . $name;
    });
});

$response = $collection->dispatch('GET', '/user/profile/janhuang'); // hello janhuang
```

## Testing

```
phpunit
```

## License MIT
