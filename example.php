<?php
/**
 * FastD Routing Component - Basic Usage Example
 * 
 * This example demonstrates the basic usage of the FastD routing component,
 * including static routes, dynamic routes, route groups, and middleware.
 */

require_once __DIR__ . '/vendor/autoload.php';

use FastD\Routing\Collection\RouteCollection;
use FastD\Http\Request\ServerRequest;
use FastD\Routing\RouteMatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Psr7\Response;

// Create a route collection
$routes = new RouteCollection();

// Define a simple static route
$routes->get('/', function (ServerRequest $request, $handler) {
    return new Response(200, [], 'Hello, World!');
});

// Define a dynamic route with parameters
$routes->get('/users/{id}', function (ServerRequest $request, $handler) {
    $id = $request->getAttribute('id');
    return new Response(200, [], 'User ID: ' . $id);
});

// Define a dynamic route with multiple parameters
$routes->get('/users/{id}/posts/{postId}', function (ServerRequest $request, $handler) {
    $id = $request->getAttribute('id');
    $postId = $request->getAttribute('postId');
    return new Response(200, [], "User ID: {$id}, Post ID: {$postId}");
});

// Define a route with optional parameter
$routes->get('/posts/{id?}', function (ServerRequest $request, $handler) {
    $id = $request->getAttribute('id');
    if ($id === null) {
        return new Response(200, [], 'List all posts');
    }
    return new Response(200, [], "Post ID: {$id}");
});

// Define a route with constraint
$routes->get('/product/{id:[0-9]+}', function (ServerRequest $request, $handler) {
    $id = $request->getAttribute('id');
    return new Response(200, [], "Product ID: {$id}");
});

// Define a route group with prefix
$routes->group('/api', function (RouteCollection $collection) {
    $collection->get('/version', function (ServerRequest $request, $handler) {
        return new Response(200, [], 'API Version 1.0');
    });
    
    $collection->get('/users', function (ServerRequest $request, $handler) {
        return new Response(200, [], 'API Users List');
    });
    
    $collection->get('/users/{id}', function (ServerRequest $request, $handler) {
        $id = $request->getAttribute('id');
        return new Response(200, [], "API User ID: {$id}");
    });
});

// Create a route matcher
$matcher = new RouteMatcher($routes);

// Simulate some HTTP requests
$requests = [
    new ServerRequest('GET', '/'),
    new ServerRequest('GET', '/users/123'),
    new ServerRequest('GET', '/users/123/posts/456'),
    new ServerRequest('GET', '/posts'),
    new ServerRequest('GET', '/posts/789'),
    new ServerRequest('GET', '/product/789'),
    new ServerRequest('GET', '/api/version'),
    new ServerRequest('GET', '/api/users'),
    new ServerRequest('GET', '/api/users/999'),
];

echo "FastD Routing Component - Basic Usage Example\n";
echo "=============================================\n\n";

foreach ($requests as $request) {
    try {
        $response = $matcher->dispatch($request);
        echo "Request: {$request->getMethod()} {$request->getUri()}\n";
        echo "Response: " . ($response ? $response->getBody() : 'No response') . "\n\n";
    } catch (Exception $e) {
        echo "Request: {$request->getMethod()} {$request->getUri()}\n";
        echo "Error: " . $e->getMessage() . "\n\n";
    }
}

// Define example middleware classes

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // In a real application, you would check for authentication here
        // For this example, we'll just add an attribute and continue
        $request = $request->withAttribute('authenticated', true);
        
        echo "AuthMiddleware: Processing request\n";
        
        // Pass the request to the next middleware/handler
        $response = $handler->handle($request);
        
        echo "AuthMiddleware: Request processed\n";
        
        return $response;
    }
}

// Define a logging middleware

class LoggingMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        echo "LoggingMiddleware: Request received for {$request->getUri()}\n";
        
        $response = $handler->handle($request);
        
        echo "LoggingMiddleware: Response sent with status {$response->getStatusCode()}\n";
        
        return $response;
    }
}

// Multi-layer middleware classes for incrementing numbers

class NumberIncrementMiddleware1 implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Save original number if not already saved
        $originalNumber = $request->getAttribute('original_number', null);
        if ($originalNumber === null) {
            $originalNumber = $request->getAttribute('number', 0);
            $request = $request->withAttribute('original_number', $originalNumber);
        }
        
        $number = $request->getAttribute('number', 0);
        $number = (int)$number + 1;
        $request = $request->withAttribute('number', $number);
        
        // Track processing history
        $processedData = $request->getAttribute('processed_data', []);
        $processedData[] = [
            'middleware' => self::class,
            'value' => $number,
            'step' => count($processedData) + 1
        ];
        $request = $request->withAttribute('processed_data', $processedData);
        
        echo "NumberIncrementMiddleware1: Incremented number to {$number}\n";
        
        return $handler->handle($request);
    }
}

class NumberIncrementMiddleware2 implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Save original number if not already saved
        $originalNumber = $request->getAttribute('original_number', null);
        if ($originalNumber === null) {
            $originalNumber = $request->getAttribute('number', 0);
            $request = $request->withAttribute('original_number', $originalNumber);
        }
        
        $number = $request->getAttribute('number', 0);
        $number = (int)$number + 1;
        $request = $request->withAttribute('number', $number);
        
        // Track processing history
        $processedData = $request->getAttribute('processed_data', []);
        $processedData[] = [
            'middleware' => self::class,
            'value' => $number,
            'step' => count($processedData) + 1
        ];
        $request = $request->withAttribute('processed_data', $processedData);
        
        echo "NumberIncrementMiddleware2: Incremented number to {$number}\n";
        
        return $handler->handle($request);
    }
}

class NumberIncrementMiddleware3 implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Save original number if not already saved
        $originalNumber = $request->getAttribute('original_number', null);
        if ($originalNumber === null) {
            $originalNumber = $request->getAttribute('number', 0);
            $request = $request->withAttribute('original_number', $originalNumber);
        }
        
        $number = $request->getAttribute('number', 0);
        $number = (int)$number + 1;
        $request = $request->withAttribute('number', $number);
        
        // Track processing history
        $processedData = $request->getAttribute('processed_data', []);
        $processedData[] = [
            'middleware' => self::class,
            'value' => $number,
            'step' => count($processedData) + 1
        ];
        $request = $request->withAttribute('processed_data', $processedData);
        
        echo "NumberIncrementMiddleware3: Incremented number to {$number}\n";
        
        return $handler->handle($request);
    }
}

// Example of controller class

class UserController
{
    public function index(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response(200, [], 'User Index Page');
    }
    
    public function show(ServerRequestInterface $request, RequestHandlerInterface $handler, ...$args): ResponseInterface
    {
        $id = $request->getAttribute('id', 'unknown');
        return new Response(200, [], "Show user {$id}");
    }
}

// Example of using controller with middleware
$controllerRoutes = new RouteCollection();
$controllerRoutes->get('/users', UserController::class.'@index', [LoggingMiddleware::class]);
$controllerRoutes->get('/users/{id}', UserController::class.'@show', [AuthMiddleware::class]);

// Example of multi-layer middleware with dynamic route
$multiMiddlewareRoutes = new RouteCollection();
$multiMiddlewareRoutes->get('/process/{number:[0-9]+}', function (ServerRequest $request, $handler) {
    $originalNumber = $request->getAttribute('original_number');
    $processedData = $request->getAttribute('processed_data', []);
    $finalValue = $request->getAttribute('number');
    
    return new Response(200, ['Content-Type' => 'application/json'], json_encode([
        'original_number' => (int)$originalNumber,
        'final_number' => (int)$finalValue,
        'processed_by' => $processedData,
        'message' => 'Processing completed successfully'
    ], JSON_PRETTY_PRINT));
}, [
    NumberIncrementMiddleware1::class,
    NumberIncrementMiddleware2::class,
    NumberIncrementMiddleware3::class
]);

$multiMiddlewareMatcher = new RouteMatcher($multiMiddlewareRoutes);

// Test multi-layer middleware
$multiMiddlewareRequest = new ServerRequest('GET', '/process/5');

try {
    $response = $multiMiddlewareMatcher->dispatch($multiMiddlewareRequest);
    echo "Multi-layer Middleware Test:\n";
    echo "Request: {$multiMiddlewareRequest->getMethod()} {$multiMiddlewareRequest->getUri()}\n";
    echo "Response: " . $response->getBody() . "\n\n";
} catch (Exception $e) {
    echo "Multi-layer Middleware Test:\n";
    echo "Request: {$multiMiddlewareRequest->getMethod()} {$multiMiddlewareRequest->getUri()}\n";
    echo "Error: " . $e->getMessage() . "\n\n";
}

$controllerMatcher = new RouteMatcher($controllerRoutes);

// Test controller routes
$userRequest = new ServerRequest('GET', '/users');
$userDetailRequest = new ServerRequest('GET', '/users/123');

$controllerRequests = [$userRequest, $userDetailRequest];

foreach ($controllerRequests as $request) {
    try {
        $response = $controllerMatcher->dispatch($request);
        echo "Request: {$request->getMethod()} {$request->getUri()}\n";
        echo "Response: " . $response->getBody() . "\n\n";
    } catch (Exception $e) {
        echo "Request: {$request->getMethod()} {$request->getUri()}\n";
        echo "Error: " . $e->getMessage() . "\n\n";
    }
}

// Demonstrate route registration
echo "Registered Routes:\n";
echo "==================\n";
[$staticRoutes, $variableRoutes] = $routes->routeMaps->getRoutes();

// Display static routes
foreach ($staticRoutes as $method => $methodRoutes) {
    foreach ($methodRoutes as $path => $route) {
        echo "- {$route->getMethod()} {$path}\n";
    }
}

// Display variable routes (in a simplified way)
// Since dynamic routes are stored as compiled regex, we can't easily convert back to original format
// So we'll just print a summary
foreach ($variableRoutes as $method => $methodRoutes) {
    echo "- {$method} [dynamic routes]\n";
}

// Example of using middleware with routes

echo "\nMiddleware Usage Example:\n";
echo "=======================\n";

// Create a new route collection for middleware examples
$middlewareRoutes = new RouteCollection();

// Add a route with middleware
$middlewareRoutes->get('/with-middleware', function (ServerRequest $request) {
    return new Response(200, [], 'Route with middleware executed');
}, [AuthMiddleware::class]);

// Add a route group with middleware
$middlewareRoutes->group('/api/v2', function (RouteCollection $collection) {
    $collection->get('/protected', function (ServerRequest $request) {
        return new Response(200, [], 'Protected API route');
    }, [AuthMiddleware::class, LoggingMiddleware::class]);
    
    $collection->get('/public', function (ServerRequest $request) {
        return new Response(200, [], 'Public API route');
    });
});

// Create matcher for middleware routes
$middlewareMatcher = new RouteMatcher($middlewareRoutes);

// Test middleware route
$middlewareRequest = new ServerRequest('GET', '/with-middleware');

try {
    $response = $middlewareMatcher->dispatch($middlewareRequest);
    echo "Request: {$middlewareRequest->getMethod()} {$middlewareRequest->getUri()}\n";
    echo "Response: " . $response->getBody() . "\n\n";
} catch (Exception $e) {
    echo "Request: {$middlewareRequest->getMethod()} {$middlewareRequest->getUri()}\n";
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Test middleware group routes
$protectedRequest = new ServerRequest('GET', '/api/v2/protected');
$publicRequest = new ServerRequest('GET', '/api/v2/public');

$requests = [$protectedRequest, $publicRequest];

foreach ($requests as $request) {
    try {
        $response = $middlewareMatcher->dispatch($request);
        echo "Request: {$request->getMethod()} {$request->getUri()}\n";
        echo "Response: " . $response->getBody() . "\n\n";
    } catch (Exception $e) {
        echo "Request: {$request->getMethod()} {$request->getUri()}\n";
        echo "Error: " . $e->getMessage() . "\n\n";
    }
}