<?php
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

class RouteCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RouteCollection
     */
    protected $collection;

    public function setUp()
    {
        $collection = new RouteCollection();

        $collection->addRoute('GET', '/', []);
        $collection->addRoute('GET', '/foo/{name}', []);
        $collection->addRoute('POST', '/foo/[{name}]', [])->withParameters(['name' => 'bar']);
        $collection->addRoute('GET', '/bar/{name}', []);
        $collection->addRoute('POST', '/foo/bar/{name}', []);
        $collection->addRoute('GET', '/hello', []);


        $this->collection = $collection;
    }

    public function testRouteMatches()
    {
        $route = $this->collection->match(new ServerRequest('GET', '/'));
        $this->assertEquals('GET', $route->getMethod());
        $this->assertTrue($route->isStatic());

        $route = $this->collection->match(new ServerRequest('GET', '/foo/bar'));
        $this->assertFalse($route->isStatic());

        $route = $this->collection->match(new ServerRequest('GET', '/foo/bar/'));
        $this->assertFalse($route->isStatic());

        $route = $this->collection->match(new ServerRequest('GET', '/hello'));
        $this->assertTrue($route->isStatic());

        $route = $this->collection->match(new ServerRequest('GET', '/hello/'));
        $this->assertTrue($route->isStatic());
    }

    public function testDefaultMatch()
    {
        $route = $this->collection->match(new ServerRequest('POST', '/foo/'));
        $this->assertEquals($route->getParameters(), ['name' => 'bar']);
        $route = $this->collection->match(new ServerRequest('POST', '/foo/demo'));
        $this->assertEquals($route->getParameters(), ['name' => 'demo']);
    }

    public function testMultiVarRouteMatch()
    {
        $this->collection->addRoute('PUT', '/{name}/{type}', []);
        $serverRequest = new ServerRequest('PUT', '/foo/bar');
        $route = $this->collection->match($serverRequest);
        $this->assertEquals([
            'name' => 'foo',
            'type' => 'bar',
        ], $route->getParameters());
    }

    public function testRouteAnyMethodMatch()
    {
        $route = $this->collection->match(new ServerRequest('POST', '/foo/bar'));
        $this->assertEquals('POST', $route->getMethod());
    }

    public function testMatchFuzzyRoute()
    {
        $this->collection->addRoute('GET', '/fuzzy/*', function (ServerRequest $request) {
            return new \FastD\Http\Response($request->getAttribute('path'));
        });

        $request1 = new ServerRequest('GET', '/fuzzy/bar');
        $request2 = new ServerRequest('GET', '/fuzzy/foo/bar');
        $route1 = $this->collection->match($request1);
        $route2 = $this->collection->match($request2);
        $this->assertEquals($route1, $route2);

        $response = call_user_func_array($route1->getCallback(), [$request1]);
        $this->assertEquals('/bar', (string) $response->getBody());

        $response = call_user_func_array($route2->getCallback(), [$request2]);
        $this->assertEquals('/foo/bar', (string) $response->getBody());
    }

    public function testGroupMiddleware()
    {
        $this->collection->group('/middleware', function () {
            $this->collection->get('/demo', '');
        });
    }

    public function testGetActiveRoute()
    {
        $route = $this->collection->match(new ServerRequest('GET', '/'));
        $this->assertEquals($route, $this->collection->getActiveRoute());
    }
}
