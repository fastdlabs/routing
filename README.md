#FastD Routing

##Requirement

* PHP 5.4+

##Composer

```
composer require fastd/routing "1.0.x-dev"
```

##Usage

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
