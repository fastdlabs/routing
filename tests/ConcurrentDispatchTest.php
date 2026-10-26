<?php

declare(strict_types=1);

use FastD\Http\Response\Text;
use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\RouteMatcher;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DemoFiberMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        Fiber::suspend($request->getUri()->getPath());

        return $handler->handle($request)->withHeader('X-Fiber', $request->getUri()->getPath());
    }
}

final class DemoFiberHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Text(200, $request->getUri()->getPath());
    }
}

/**
 * 并发/交错请求下，RouteMatcher 不能共享中间件栈。
 */
final class ConcurrentDispatchTest extends TestCase
{
    public function testInterleavedDispatchesKeepTheirOwnMiddlewareStack(): void
    {
        $collection = new RouteCollection();
        $collection->get('/a', DemoFiberHandler::class, [DemoFiberMiddleware::class]);
        $collection->get('/b', DemoFiberHandler::class, [DemoFiberMiddleware::class]);
        $collection->get('/plain', DemoFiberHandler::class);

        $matcher = new RouteMatcher($collection);

        $fiberA = new Fiber(fn () => $matcher->dispatch(new ServerRequest('GET', '/a')));
        $fiberB = new Fiber(fn () => $matcher->dispatch(new ServerRequest('GET', '/b')));

        $fiberA->start();
        $fiberB->start();

        $fiberA->resume();
        $fiberB->resume();

        /** @var ResponseInterface $responseA */
        $responseA = $fiberA->getReturn();
        /** @var ResponseInterface $responseB */
        $responseB = $fiberB->getReturn();

        $this->assertSame('/a', (string) $responseA->getBody());
        $this->assertSame('/a', $responseA->getHeaderLine('X-Fiber'));
        $this->assertSame('/b', (string) $responseB->getBody());
        $this->assertSame('/b', $responseB->getHeaderLine('X-Fiber'));
        $this->assertTrue($fiberA->isTerminated());
        $this->assertTrue($fiberB->isTerminated());

        // 共享栈不能因为请求级中间件而增长
        $stack = new ReflectionProperty(RouteMatcher::class, 'splStack');
        $stack->setAccessible(true);

        for ($i = 0; $i < 100; $i++) {
            $matcher->dispatch(new ServerRequest('GET', '/plain'));
        }

        $this->assertCount(0, $stack->getValue($matcher));
    }
}
