<?php

declare(strict_types=1);

use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\RouteMatcher;
use FastD\Routing\Matched;
use FastD\Routing\Exceptions\RouteException;
use FastD\Routing\Exceptions\RouteNotFoundException;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * 增强的动态路由测试
 * 专门测试复杂的动态路由场景和边界情况
 */
class DynamicRouteEnhancedTest extends TestCase
{
    private RouteCollection $routeCollection;
    private RouteMatcher $routeMatcher;

    protected function setUp(): void
    {
        $this->routeCollection = new RouteCollection();
        $this->routeMatcher = new RouteMatcher($this->routeCollection);
    }

    /**
     * 测试多个动态参数的路由匹配
     */
    public function testMultipleDynamicParameters(): void
    {
        $this->routeCollection->get('/users/{userId}/posts/{postId}/comments/{commentId}', 'MultiParamController@handle');
        
        $request = new ServerRequest('GET', '/users/123/posts/456/comments/789');
        $matched = $this->routeMatcher->match($request);
        
        $this->assertInstanceOf(Matched::class, $matched);
        $this->assertEquals('MultiParamController@handle', $matched->getRoute()->getHandler());
        
        // 验证所有参数都被正确匹配
        $requestWithAttributes = $matched->getServerRequest();
        $this->assertEquals('123', $requestWithAttributes->getAttribute('userId'));
        $this->assertEquals('456', $requestWithAttributes->getAttribute('postId'));
        $this->assertEquals('789', $requestWithAttributes->getAttribute('commentId'));
    }

    /**
     * 测试动态路由不能覆盖静态路由
     * 验证路由系统中静态路由优先于动态路由的注册规则
     */
    public function testStaticRoutePriorityOverDynamic(): void
    {
        // 先定义动态路由
        $this->routeCollection->get('/users/{id}', 'DynamicController@show');
        
        // 然后尝试定义冲突的静态路由，这会抛出异常
        $this->expectException(\FastD\Routing\Exceptions\RouteException::class);
        $this->expectExceptionMessage('shadowed');
        $this->routeCollection->get('/users/profile', 'StaticController@profile');
    }

    /**
     * 测试复杂正则表达式的动态路由
     */
    public function testComplexRegexDynamicRoute(): void
    {
        // UUID 格式
        $this->routeCollection->get('/api/documents/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', 'DocumentController@show');
        // 数字范围限制
        $this->routeCollection->get('/products/{id:[1-9][0-9]{0,5}}', 'ProductController@show');
        // 字母数字组合
        $this->routeCollection->get('/tags/{tag:[a-zA-Z0-9_-]+}', 'TagController@show');
        
        // 测试 UUID 格式
        $uuidRequest = new ServerRequest('GET', '/api/documents/550e8400-e29b-41d4-a716-446655440000');
        $uuidMatched = $this->routeMatcher->match($uuidRequest);
        $this->assertEquals('DocumentController@show', $uuidMatched->getRoute()->getHandler());
        
        // 测试产品ID格式
        $productRequest = new ServerRequest('GET', '/products/12345');
        $productMatched = $this->routeMatcher->match($productRequest);
        $this->assertEquals('ProductController@show', $productMatched->getRoute()->getHandler());
        
        // 测试标签格式
        $tagRequest = new ServerRequest('GET', '/tags/php-unit-test');
        $tagMatched = $this->routeMatcher->match($tagRequest);
        $this->assertEquals('TagController@show', $tagMatched->getRoute()->getHandler());
    }

    /**
     * 测试特殊字符在动态路由参数中的处理
     */
    public function testSpecialCharactersInDynamicParameters(): void
    {
        $this->routeCollection->get('/search/{query}', 'SearchController@query');
        
        // 测试包含空格的参数（URL编码）
        $spaceRequest = new ServerRequest('GET', '/search/hello%20world');
        $spaceMatched = $this->routeMatcher->match($spaceRequest);
        $this->assertEquals('SearchController@query', $spaceMatched->getRoute()->getHandler());
        // URL 解码处理
        $decodedQuery = urldecode($spaceMatched->getServerRequest()->getAttribute('query'));
        $this->assertEquals('hello world', $decodedQuery);
        
        // 测试包含特殊符号的参数
        $specialRequest = new ServerRequest('GET', '/search/test%2Bplus%26ampersand');
        $specialMatched = $this->routeMatcher->match($specialRequest);
        $decodedSpecial = urldecode($specialMatched->getServerRequest()->getAttribute('query'));
        $this->assertEquals('test+plus&ampersand', $decodedSpecial);
    }

    /**
     * 测试动态路由参数的边界值
     */
    public function testDynamicParameterEdgeCases(): void
    {
        $this->routeCollection->get('/items/{id:[0-9]+}', 'ItemController@show');
        
        // 测试最小值
        $minRequest = new ServerRequest('GET', '/items/0');
        $minMatched = $this->routeMatcher->match($minRequest);
        $this->assertEquals('0', $minMatched->getServerRequest()->getAttribute('id'));
        
        // 测试大数值
        $largeRequest = new ServerRequest('GET', '/items/999999999');
        $largeMatched = $this->routeMatcher->match($largeRequest);
        $this->assertEquals('999999999', $largeMatched->getServerRequest()->getAttribute('id'));
    }

    /**
     * 测试嵌套动态路由组
     */
    public function testNestedDynamicRouteGroups(): void
    {
        $this->routeCollection->group('/api/v1', function ($router) {
            $router->group('/users/{userId}', function ($subRouter) {
                $subRouter->get('/posts/{postId}', 'NestedController@getPost');
                $subRouter->get('/profile', 'NestedController@getUserProfile');
            });
        });
        
        // 测试嵌套路由
        $nestedRequest = new ServerRequest('GET', '/api/v1/users/123/posts/456');
        $nestedMatched = $this->routeMatcher->match($nestedRequest);
        $this->assertEquals('NestedController@getPost', $nestedMatched->getRoute()->getHandler());
        $this->assertEquals('123', $nestedMatched->getServerRequest()->getAttribute('userId'));
        $this->assertEquals('456', $nestedMatched->getServerRequest()->getAttribute('postId'));
        
        // 测试同一组内的不同路由
        $profileRequest = new ServerRequest('GET', '/api/v1/users/123/profile');
        $profileMatched = $this->routeMatcher->match($profileRequest);
        $this->assertEquals('NestedController@getUserProfile', $profileMatched->getRoute()->getHandler());
        $this->assertEquals('123', $profileMatched->getServerRequest()->getAttribute('userId'));
    }

    /**
     * 测试动态路由匹配失败的情况
     */
    public function testDynamicRouteMatchingFailure(): void
    {
        $this->routeCollection->get('/strict/{id:[0-9]+}', 'StrictController@show');
        
        // 不符合正则表达式的请求应该抛出异常
        $invalidRequest = new ServerRequest('GET', '/strict/abc');
        
        $this->expectException(RouteNotFoundException::class);
        $this->routeMatcher->match($invalidRequest);
    }

    /**
     * 测试相同前缀的动态路由区分
     */
    public function testSimilarPrefixDynamicRoutes(): void
    {
        $this->routeCollection->get('/files/{filename}', 'FileController@show');
        $this->routeCollection->get('/files/download/{filename}', 'FileController@download');
        $this->routeCollection->get('/files/info/{filename}', 'FileController@info');
        
        // 测试基础文件路由
        $basicRequest = new ServerRequest('GET', '/files/document.pdf');
        $basicMatched = $this->routeMatcher->match($basicRequest);
        $this->assertEquals('FileController@show', $basicMatched->getRoute()->getHandler());
        $this->assertEquals('document.pdf', $basicMatched->getServerRequest()->getAttribute('filename'));
        
        // 测试下载路由
        $downloadRequest = new ServerRequest('GET', '/files/download/image.jpg');
        $downloadMatched = $this->routeMatcher->match($downloadRequest);
        $this->assertEquals('FileController@download', $downloadMatched->getRoute()->getHandler());
        $this->assertEquals('image.jpg', $downloadMatched->getServerRequest()->getAttribute('filename'));
        
        // 测试信息路由
        $infoRequest = new ServerRequest('GET', '/files/info/readme.txt');
        $infoMatched = $this->routeMatcher->match($infoRequest);
        $this->assertEquals('FileController@info', $infoMatched->getRoute()->getHandler());
        $this->assertEquals('readme.txt', $infoMatched->getServerRequest()->getAttribute('filename'));
    }
}

// 测试控制器类（用于类型提示）
class MultiParamController { public function handle() {} }
class DynamicController { public function show() {} }
class StaticController { public function profile() {} }
class DocumentController { public function show() {} }
class ProductController { public function show() {} }
class TagController { public function show() {} }
class SearchController { public function query() {} }
class ItemController { public function show() {} }
class NestedController { public function getPost() {} public function getUserProfile() {} }
class StrictController { public function show() {} }
class FileController { public function show() {} public function download() {} public function info() {} }