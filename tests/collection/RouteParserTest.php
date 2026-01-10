<?php

declare(strict_types=1);

use FastD\Routing\Collection\RouteParser;
use FastD\Routing\Exceptions\RouteException;
use PHPUnit\Framework\TestCase;

class RouteParserTest extends TestCase
{
    private RouteParser $parser;

    protected function setUp(): void
    {
        $this->parser = new RouteParser();
    }

    public function testParseBasicVariables(): void
    {
        $parsed = $this->parser->parse('/{name}/{age}');
        
        $expected = [
            [
                '/',
                ['name', '[^/]+'],
                '/',
                ['age', '[^/]+']
            ]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseCustomRegexVariables(): void
    {
        $parsed = $this->parser->parse('/users/{id:[0-9]+}/posts/{slug:[a-zA-Z0-9-]+}');
        
        $expected = [
            [
                '/users/',
                ['id', '[0-9]+'],
                '/posts/',
                ['slug', '[a-zA-Z0-9-]+']
            ]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseOptionalSegments(): void
    {
        $parsed = $this->parser->parse('/users[/{id:[0-9]+}]');
        
        $expected = [
            ['/users'],
            ['/users/', ['id', '[0-9]+']]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseNestedOptionalSegments(): void
    {
        $parsed = $this->parser->parse('/api[/{version}[/{resource}]]');
        
        $expected = [
            ['/api'],
            ['/api/', ['version', '[^/]+']],
            ['/api/', ['version', '[^/]+'], '/', ['resource', '[^/]+']]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseStaticRoute(): void
    {
        $parsed = $this->parser->parse('/users/profile');
        
        $expected = [['/users/profile']];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseMixedStaticAndVariables(): void
    {
        $parsed = $this->parser->parse('/blog/{year:[0-9]{4}}/{month:[0-9]{2}}/{day:[0-9]{2}}/{slug}');
        
        $expected = [
            [
                '/blog/',
                ['year', '[0-9]{4}'],
                '/',
                ['month', '[0-9]{2}'],
                '/',
                ['day', '[0-9]{2}'],
                '/',
                ['slug', '[^/]+']
            ]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseSpecialCharactersInVariableNames(): void
    {
        $parsed = $this->parser->parse('/files/{file_name?*}');
        
        $expected = [
            [
                '/files/',
                ['file_name?*', '[^/]+']
            ]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseEmptyOptionalSegmentThrowsException(): void
    {
        $this->expectException(RouteException::class);
        $this->expectExceptionMessage('Optional segments can only occur at the end of a route');
        
        $this->parser->parse('/users[/][/posts]');
    }

    public function testParseMismatchedBrackets(): void
    {
        $parsed = $this->parser->parse('/users[{id}]');
        
        $expected = [
            ['/users'],
            ['/users', ['id', '[^/]+']]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseSpecialSyntax(): void
    {
        $parsed = $this->parser->parse('/users]/{id}');
        
        $expected = [
            ['/users]/', ['id', '[^/]+']]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseComplexNestedRegex(): void
    {
        $parsed = $this->parser->parse('/search/{query:(?i)[a-z]+}');
        
        $expected = [
            [
                '/search/',
                ['query', '(?i)[a-z]+']
            ]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseMultipleCallsReturnConsistentResults(): void
    {
        $route = '/api/v{version:[0-9]+}/users/{id:[0-9]+}';
        
        $firstResult = $this->parser->parse($route);
        $secondResult = $this->parser->parse($route);
        
        $this->assertEquals($firstResult, $secondResult);
        $this->assertTrue($firstResult == $secondResult);
    }

    public function testParseRootPath(): void
    {
        $parsed = $this->parser->parse('/');
        
        $expected = [['/']];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseEmptyString(): void
    {
        $parsed = $this->parser->parse('');
        
        $expected = [['']];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParsePerformanceWithManyVariables(): void
    {
        $complexRoute = '/{section}/{category}/{subcategory}/{product}/{variant}/{color}/{size}/{material}';
        
        $startTime = microtime(true);
        $result = $this->parser->parse($complexRoute);
        $endTime = microtime(true);
        
        $executionTime = $endTime - $startTime;
        
        $this->assertLessThan(0.1, $executionTime);
        $this->assertCount(1, $result);
        $this->assertCount(16, $result[0]); // 8 variables + 8 separators
    }

    public function testParseEmailPattern(): void
    {
        $parsed = $this->parser->parse('/users/{email:[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}}');
        
        $expected = [
            [
                '/users/',
                ['email', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}']
            ]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseUuidPattern(): void
    {
        $parsed = $this->parser->parse('/resources/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}');
        
        $expected = [
            [
                '/resources/',
                ['uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']
            ]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseUnicodeCharacters(): void
    {
        $parsed = $this->parser->parse('/国际/{name:[\x{4e00}-\x{9fa5}]+}');
        
        $expected = [
            [
                '/国际/',
                ['name', '[\x{4e00}-\x{9fa5}]+']
            ]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseMultipleOptionalSegments(): void
    {
        $parsed = $this->parser->parse('/api[/{version}[/{module}[/{action}]]]');
        
        $expected = [
            ['/api'],
            ['/api/', ['version', '[^/]+']],
            ['/api/', ['version', '[^/]+'], '/', ['module', '[^/]+']],
            ['/api/', ['version', '[^/]+'], '/', ['module', '[^/]+'], '/', ['action', '[^/]+']]
        ];
        
        $this->assertEquals($expected, $parsed);
    }

    public function testParseAdjacentVariables(): void
    {
        $parsed = $this->parser->parse('/{prefix}{suffix}');
        
        // 验证解析结果
        $this->assertCount(1, $parsed); // 应该只有一个路由模式
        $this->assertCount(3, $parsed[0]); // 应该有三个部分：/, prefix变量, suffix变量
        
        // 验证第一个元素是根路径分隔符
        $this->assertEquals('/', $parsed[0][0]);
        
        // 验证第二个元素是 prefix 变量
        $this->assertIsArray($parsed[0][1]);
        $this->assertEquals('prefix', $parsed[0][1][0]);
        $this->assertEquals('[^/]+', $parsed[0][1][1]);
        
        // 验证第三个元素是 suffix 变量
        $this->assertIsArray($parsed[0][2]);
        $this->assertEquals('suffix', $parsed[0][2][0]);
        $this->assertEquals('[^/]+', $parsed[0][2][1]);
    }
}