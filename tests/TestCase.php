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
class TestCase extends PHPUnit_Framework_TestCase
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
        $collection->addRoute('POST', '/foo/{name}', [], ['name' => 'bar']);

        $collection->addRoute('GET', '/bar/{name}', []);

        $collection->addRoute('POST', '/foo/bar/{name}', []);

        $collection->addRoute('GET', '/hello', []);

        $collection->addRoute('GET', '/fuzzy/*', function (ServerRequest $request) {
            return new \FastD\Http\Response($request->getAttribute('path'));
        });

        $this->collection = $collection;

        include_once __DIR__ . '/middleware/AfterMiddleware.php';
        include_once __DIR__ . '/middleware/BreakerMiddleware.php';
        include_once __DIR__ . '/middleware/BeforeMiddleware.php';
        include_once __DIR__ . '/middleware/DefaultMiddleware.php';
    }

    public function createRequest($method, $path)
    {
        return new ServerRequest($method, $path);
    }
}