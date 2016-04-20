# FastD Routing

简单的 PHP 路由器，支持路由嵌套，动态路由等。

## 要求

* PHP 7+

## Composer

```
composer require "fastd/routing:2.0-dev"
```

## 使用

可以通过 `Router` 对象设置路由，也可以通过路由列表创建路由.

### 路由器

```php
$router = new FastD\Routing\Router();
$router->addRoute('name', 'GET', '/', function () {
    return 'hello world';
});
$callback = $router->dispatch('GET', '/');
```

### 路由列表

```php
Routes::get('name', '/', function () {
    return 'hello world';
});

$callback = Routes::getRouter()->dispatch('GET', '/');
```

### 路由支持多个请求方法

```php
Routes::get('name_get', '/', function () {
    return 'hello world';
});

Routes::post('name_post', '/', function () {
    return 'hello world';
});

$callback = Routes::getRouter()->dispatch('POST', '/');
```

## Testing

```
cd path/to/routing
composer install
phpunit
```

## License MIT
