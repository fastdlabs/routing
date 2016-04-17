# FastD Routing

简单的 PHP 路由器，支持路由嵌套，动态路由等。

## 要求

* PHP 7+

## Composer

```
composer require "fastd/routing:2.0-dev"
```

## 使用

```php
$router = new FastD\Routing\Router();
$router->addRoute('name', 'GET', '/', function () {
    return 'hello world';
});
$response = $router->dispatch('/');
```

## Testing

```
cd path/to/routing
composer install
phpunit
```
