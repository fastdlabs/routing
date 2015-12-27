#FastD Routing

##要求

* PHP 7+

##Composer

```
composer require "fastd/routing:2.0-dev"
```

##使用

```php
$router = new FastD\Routing\Router();
$router->addRoute($name, '/', $callback);
$response = $router->dispatch("/");
```

##Testing

```
cd path/to/routing
composer install
phpunit
```
