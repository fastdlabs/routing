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

final class ResolverTestDependency
{
    public string $value = 'resolved dependency';
}

final class ResolverTestMiddleware implements MiddlewareInterface
{
    public function __construct(public readonly ResolverTestDependency $dependency)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request)->withHeader('X-Resolver-Middleware', $this->dependency->value);
    }
}

final class ResolverTestController implements MiddlewareInterface
{
    public function __construct(public readonly ResolverTestDependency $dependency)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Text(200, $this->dependency->value);
    }
}

final class ResolverDefaultController implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Text(200, 'default resolver');
    }
}

final class ResolverTest extends TestCase
{
    public function testResolverResolvesMiddlewareAndHandler(): void
    {
        $dependency = new ResolverTestDependency();

        $resolver = static function (string $class) use ($dependency): object {
            return match ($class) {
                ResolverTestMiddleware::class => new ResolverTestMiddleware($dependency),
                ResolverTestController::class => new ResolverTestController($dependency),
                default => new $class(),
            };
        };

        $collection = new RouteCollection();
        $collection->get('/resolver', ResolverTestController::class, [ResolverTestMiddleware::class]);

        $response = (new RouteMatcher($collection, $resolver))
            ->dispatch(new ServerRequest('GET', '/resolver'));

        $this->assertSame('resolved dependency', (string) $response->getBody());
        $this->assertSame('resolved dependency', $response->getHeaderLine('X-Resolver-Middleware'));
    }

    public function testDefaultResolverKeepsNewClassBehavior(): void
    {
        $collection = new RouteCollection();
        $collection->get('/default', ResolverDefaultController::class);

        $response = (new RouteMatcher($collection))
            ->dispatch(new ServerRequest('GET', '/default'));

        $this->assertSame('default resolver', (string) $response->getBody());
    }
}
