<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;
use PHPUnit\Framework\TestCase;

class RouteDispatcherTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        require_once __DIR__ . '/handle/hello.php';
        require_once __DIR__ . '/middleware/AfterMiddleware.php';
        require_once __DIR__ . '/middleware/BeforeMiddleware.php';
    }

    public function testBaseDispatch()
    {
        $collections = new RouteCollection();

        $collections->get('/')->handle(hello::class);

        $dispatcher = new RouteDispatcher($collections);

        $response = $dispatcher->dispatch(new ServerRequest('GET', '/'));

        $content = $response->getBody();

        $this->assertEquals('hello', $content);
    }

    public function testAfterMiddlewareDispatch()
    {
        $collections = new RouteCollection();

        $collections->get('/')->handle(hello::class)->addMiddleware(AfterMiddleware::class);

        $dispatcher = new RouteDispatcher($collections);

        $response = $dispatcher->dispatch(new ServerRequest('GET', '/'));

        $content = $response->getBody();

        echo $content;
    }

    public function testBeforeMiddlewareDispatch()
    {
        $collections = new RouteCollection();

        $collections->get('/')
            ->handle(hello::class)
            ->after(BeforeMiddleware::class);

        $dispatcher = new RouteDispatcher($collections);

        $response = $dispatcher->dispatch(new ServerRequest('GET', '/'));

        $content = $response->getBody();

        echo $content;
    }
}
