<?php

declare(strict_types=1);

use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\RouteMatcher;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;

class RouteMatcherConcurrencyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $id = (string) $request->getAttribute('id');
        Coroutine::sleep(0.002);

        return $handler->handle($request)->withHeader('X-Coro-Id', $id);
    }
}

class RouteMatcherConcurrencyTest extends TestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('swoole')) {
            $this->markTestSkipped('ext-swoole is required for coroutine concurrency tests.');
        }
    }

    public function testConcurrentDispatchKeepsMatchedStateIsolated(): void
    {
        $collection = new RouteCollection();
        $collection->get('/coro/{id}', static function (ServerRequestInterface $request): ResponseInterface {
            Coroutine::sleep(0.002);

            return new Response(200, [], (string) $request->getAttribute('id'));
        }, [RouteMatcherConcurrencyMiddleware::class]);

        $matcher = new RouteMatcher($collection);
        $total = 100;
        $results = [];

        Coroutine\run(function () use ($matcher, $total, &$results): void {
            $channel = new Channel($total);

            for ($i = 0; $i < $total; $i++) {
                Coroutine::create(static function () use ($matcher, $channel, $i): void {
                    $response = $matcher->dispatch(new ServerRequest('GET', '/coro/' . $i));
                    $channel->push([
                        'expected' => (string) $i,
                        'body' => (string) $response->getBody(),
                        'header' => $response->getHeaderLine('X-Coro-Id'),
                    ]);
                });
            }

            for ($i = 0; $i < $total; $i++) {
                $results[] = $channel->pop();
            }
        });

        $this->assertCount($total, $results);

        foreach ($results as $result) {
            $this->assertSame($result['expected'], $result['body']);
            $this->assertSame($result['expected'], $result['header']);
        }
    }
}
