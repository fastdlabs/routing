<?php

declare(strict_types=1);

namespace collection;

use FastD\Routing\Collection\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testConstructorCreatesRouteWithCorrectProperties(): void
    {
        $method = 'GET';
        $handler = 'UserController@index';
        $regex = 'users/(\d+)';
        $variables = ['id'];
        $middlewares = ['auth', 'cors'];
        $parameters = ['param1' => 'value1'];

        $route = new Route($method, $handler, $regex, $variables, $middlewares, $parameters);

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals($method, $route->getMethod());
        $this->assertEquals($regex, $route->getRegex());
        $this->assertEquals($variables, $route->getVariables());
        $this->assertEquals($parameters, $route->getParameters()['definition']);
        $this->assertEquals($middlewares, $route->getMiddlewares());
        $this->assertEquals($handler, $route->getHandler());
    }

    public function testMatchReturnsTrueForMatchingString(): void
    {
        $route = new Route('GET', 'test', 'users/(\d+)', ['id']);

        $this->assertTrue($route->match('users/123'));
        $this->assertTrue($route->match('users/1'));
    }

    public function testMatchReturnsFalseForNonMatchingString(): void
    {
        $route = new Route('GET', 'test', 'users/(\d+)', ['id']);

        $this->assertFalse($route->match('users/abc'));
        $this->assertFalse($route->match('posts/123'));
        $this->assertFalse($route->match('users/'));
    }

    public function testGetParametersReturnsInitialParameters(): void
    {
        $parameters = ['param1' => 'value1', 'param2' => 'value2'];
        $route = new Route('GET', 'test', '', [], [], $parameters);

        $this->assertEquals($parameters, $route->getParameters()['definition']);
    }

    public function testSetMatchedVariablesUpdatesParameters(): void
    {
        $initialParameters = ['param1' => 'value1'];
        $matchedVariables = ['id' => '123', 'name' => 'test'];
        $route = new Route('GET', 'test', '', [], [], $initialParameters);

        $result = $route->setMatchedVariables($matchedVariables);

        $this->assertInstanceOf(Route::class, $result); // Should return $this for chaining
        $this->assertEquals($matchedVariables, $route->getParameters()['matched']);
    }

    public function testAddMiddlewareAddsMiddlewareToArray(): void
    {
        $route = new Route('GET', 'test', '', []);

        $result = $route->addMiddleware('auth');

        $this->assertInstanceOf(Route::class, $result); // Should return $this for chaining
        $this->assertContains('auth', $route->getMiddlewares());
        $this->assertCount(1, $route->getMiddlewares());
    }

    public function testAddMultipleMiddlewares(): void
    {
        $route = new Route('GET', 'test', '', []);

        $route->addMiddleware('auth');
        $route->addMiddleware('cors');

        $middlewares = $route->getMiddlewares();
        $this->assertCount(2, $middlewares);
        $this->assertContains('auth', $middlewares);
        $this->assertContains('cors', $middlewares);
    }

    public function testSetMiddlewaresReplacesAllMiddlewares(): void
    {
        $route = new Route('GET', 'test', '', []);
        $route->addMiddleware('first');

        $newMiddlewares = ['auth', 'cors', 'throttle'];
        $result = $route->setMiddlewares($newMiddlewares);

        $this->assertInstanceOf(Route::class, $result); // Should return $this for chaining
        $this->assertEquals($newMiddlewares, $route->getMiddlewares());
    }

    public function testGetMiddlewaresReturnsEmptyArrayByDefault(): void
    {
        $route = new Route('GET', 'test', '', []);

        $this->assertEquals([], $route->getMiddlewares());
    }

    public function testGetMethodReturnsCorrectMethod(): void
    {
        $method = 'POST';
        $route = new Route($method, 'test', '', []);

        $this->assertEquals($method, $route->getMethod());
    }

    public function testGetRegexReturnsCorrectRegex(): void
    {
        $regex = 'users/(\d+)/posts/(\w+)';
        $route = new Route('GET', 'test', $regex, ['id', 'slug']);

        $this->assertEquals($regex, $route->getRegex());
    }

    public function testGetVariablesReturnsCorrectVariables(): void
    {
        $variables = ['id', 'slug', 'category'];
        $route = new Route('GET', 'test', 'pattern', $variables);

        $this->assertEquals($variables, $route->getVariables());
    }

    public function testGetHandlerReturnsCorrectHandler(): void
    {
        $handler = 'UserController@show';
        $route = new Route('GET', $handler, '', []);

        $this->assertEquals($handler, $route->getHandler());
    }

    public function testImmutablePropertiesAfterConstruction(): void
    {
        $route = new Route('GET', 'handler', 'regex', ['var'], ['middleware'], ['param' => 'value']);

        // Test that we can't modify immutable properties externally
        // The properties are protected, so we test through public methods
        $this->assertEquals('GET', $route->getMethod());
        $this->assertEquals('handler', $route->getHandler());
        $this->assertEquals('regex', $route->getRegex());
        $this->assertEquals(['var'], $route->getVariables());
    }
}