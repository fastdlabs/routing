<?php
declare(strict_types=1);

namespace FastD\Routing;

use FastD\Routing\Exceptions\RouteException;

class RouteParser
{
    public const VARIABLE_REGEX = <<<'REGEX'
\{
    ([a-zA-Z0-9_?*]*)
    (?:
        :([^{}]*(?:\{(?-1)\}[^{}]*)*)
    )?
\}
REGEX;

    public const DEFAULT_DISPATCH_REGEX = '[^/]+';

    /**
     * @param string $path
     * @return array
     */
    public function parse(string $path): array
    {
        $routeWithoutClosingOptionals = rtrim($path, ']');
        $numOptionals = strlen($path) - strlen($routeWithoutClosingOptionals);

        // Split on [ while skipping placeholders
        $segments = preg_split('~' . self::VARIABLE_REGEX . '(*SKIP)(*F) | \[~x', $routeWithoutClosingOptionals);

        if ($numOptionals !== count($segments) - 1) {
            // If there are any ] in the middle of the route, throw a more specific error message
            if (preg_match('~' . self::VARIABLE_REGEX . '(*SKIP)(*F) | \]~x', $routeWithoutClosingOptionals)) {
                throw new RouteException('Optional segments can only occur at the end of a route');
            }

            throw new RouteException("Number of opening '[' and closing ']' does not match");
        }

        $currentRoute = '';
        $routeDatas = [];

        foreach ($segments as $n => $segment) {
            if ($segment === '' && $n !== 0) {
                throw new RouteException('Empty optional part');
            }

            $currentRoute .= $segment;
            $routeDatas[] = $this->parsePlaceholders($currentRoute);
        }

        return $routeDatas;
    }

    /**
     * Parses a route string that does not contain optional segments.
     * @param string $route
     * @return array
     */
    private function parsePlaceholders(string $route): array
    {
        if (! preg_match_all('~' . self::VARIABLE_REGEX . '~x', $route, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) {
            return [$route];
        }

        $offset = 0;
        $routeData = [];

        foreach ($matches as $set) {
            if ($set[0][1] > $offset) {
                $routeData[] = substr($route, $offset, $set[0][1] - $offset);
            }

            $routeData[] = [
                $set[1][0],
                isset($set[2]) ? trim($set[2][0]) : self::DEFAULT_DISPATCH_REGEX,
            ];

            $offset = $set[0][1] + strlen($set[0][0]);
        }

        if ($offset !== strlen($route)) {
            $routeData[] = substr($route, $offset);
        }

        return $routeData;
    }
}
