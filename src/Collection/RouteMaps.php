<?php

declare(strict_types=1);

namespace FastD\Routing\Collection;

use FastD\Routing\Exceptions\RouteException;

class RouteMaps
{
    private const ROUTES_CHUNK = 30;

    protected array $staticRoutes = [];

    protected array $methodToRegexToRoutesMap = [];

    public function addRoute(string $method, array $routeData, $handler, array $middleware, array $parameters): void
    {
        if ($this->isStaticRoute($routeData)) {
            $this->addStaticRoute($method, $routeData, $handler, $middleware, $parameters);
        } else {
            $this->addVariableRoute($method, $routeData, $handler, $middleware, $parameters);
        }
    }

    public function getRoutes(): array
    {
        if ($this->methodToRegexToRoutesMap === []) {
            return [$this->staticRoutes, []];
        }

        return [$this->staticRoutes, $this->generateVariableRoutes()];
    }

    private function isStaticRoute(array $routeData): bool
    {
        return count($routeData) === 1 && is_string($routeData[0]);
    }

    private function addStaticRoute(string $method, array $routeData, $handler, array $middleware, array $parameters): void
    {
        $routeStr = $routeData[0];

        if (isset($this->staticRoutes[$method][$routeStr])) {
            throw new RouteException(sprintf(
                'Cannot register two routes matching "%s" for method "%s"',
                $routeStr,
                $method
            ));
        }

        if (isset($this->methodToRegexToRoutesMap[$method])) {
            foreach ($this->methodToRegexToRoutesMap[$method] as $route) {
                if ($route->match($routeStr)) {
                    throw new RouteException(sprintf(
                        'Static route "%s" is shadowed by previously defined variable route "%s" for method "%s"',
                        $routeStr,
                        $route->getRegex(),
                        $method
                    ));
                }
            }
        }

        $this->staticRoutes[$method][$routeStr] = new Route(
            $method,
            $handler,
            '',
            [],
            $middleware,
            $parameters
        );
    }

    private function addVariableRoute(string $httpMethod, array $routeData, $handler, array $middleware, array $parameters): void
    {
        [$regex, $variables] = $this->buildRegexForRoute($routeData);

        if (isset($this->methodToRegexToRoutesMap[$httpMethod][$regex])) {
            throw new RouteException(sprintf(
                'Cannot register two routes matching "%s" for method "%s"',
                $regex,
                $httpMethod
            ));
        }

        $this->methodToRegexToRoutesMap[$httpMethod][$regex] = new Route(
            $httpMethod,
            $handler,
            $regex,
            $variables,
            $middleware,
            $parameters
        );
    }

    private function buildRegexForRoute(array $routeData): array
    {
        $regex = '';
        $variables = [];
        foreach ($routeData as $part) {
            if (is_string($part)) {
                $regex .= preg_quote($part, '~');
                continue;
            }

            [$varName, $regexPart] = $part;

            if (isset($variables[$varName])) {
                throw new RouteException(sprintf(
                    'Cannot use the same placeholder "%s" twice',
                    $varName
                ));
            }

            if ($this->regexHasCapturingGroups($regexPart)) {
                throw new RouteException(sprintf(
                    'Regex "%s" for parameter "%s" contains a capturing group',
                    $regexPart,
                    $varName
                ));
            }

            $variables[$varName] = $varName;
            $regex .= '(' . $regexPart . ')';
        }

        return [$regex, $variables];
    }

    private function regexHasCapturingGroups(string $regex): bool
    {
        if (!str_contains($regex, '(')) {
            // Needs to have at least a ( to contain a capturing group
            return false;
        }

        // Semi-accurate detection for capturing groups
        return (bool) preg_match(
            '~
                (?:
                    \(\?\(
                  | \[ [^]\\\\]* (?: \\\\ . [^]\\\\]* )* ]
                  | \\\\ .
                ) (*SKIP)(*FAIL) |
                \(
                (?!
                    \? (?! <(?![!=]) | P< | \' )
                  | \*
                )
            ~x',
            $regex
        );
    }

    private function generateVariableRoutes(): array
    {
        $data = [];
        foreach ($this->methodToRegexToRoutesMap as $method => $regexToRoutesMap) {
            $chunkSize = $this->computeChunkSize(count($regexToRoutesMap));
            $chunks = array_chunk($regexToRoutesMap, $chunkSize, true);
            $data[$method] = array_map([$this, 'processChunk'], $chunks);
        }

        return $data;
    }

    private function computeChunkSize(int $count): int
    {
        $numParts = max(1, round($count / self::ROUTES_CHUNK));

        return (int) ceil($count / $numParts);
    }

    protected function processChunk(array $regexToRoutesMap): array
    {
        $routeMap = [];
        $regexes = [];
        $markName = 'a';

        foreach ($regexToRoutesMap as $regex => $route) {
            $regexes[] = $regex . '(*MARK:' . $markName . ')';
            //$routeMap[$markName] = [$route->handler, $route->variables];
            $routeMap[$markName] = $route;

            ++$markName;
        }

        $regex = '~^(?|' . implode('|', $regexes) . ')$~';

        return ['regex' => $regex, 'routeMap' => $routeMap];
    }
}
