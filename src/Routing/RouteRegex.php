<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;

/**
 * Class RouteRegex
 *
 * @package FastD\Routing
 */
class RouteRegex
{
    const VARIABLE_REGEX = '{\s*([a-zA-Z][a-zA-Z0-9_]*)\s*}';

    const DEFAULT_DISPATCH_REGEX = '[^/]+';

    /**
     * @param array $routeInfo
     * @return string
     * @throws \Exception
     */
    private function buildRouteRegex(array $routeInfo)
    {
        $regex = '';
        foreach ($routeInfo as $part) {
            if (is_string($part)) {
                $regex .= preg_quote($part, '~');
                continue;
            }

            list($varName, $regexPart) = $part;

            if ($this->regexHasCapturingGroups($regexPart)) {
                throw new \Exception(sprintf(
                    'Regex "%s" for parameter "%s" contains a capturing group',
                    $regexPart, $varName
                ));
            }

            $regex .= '(?P<' . $varName . '>' . $regexPart . ')';
        }

        return $regex;
    }

    /**
     * @param $regex
     * @return bool|int
     */
    private function regexHasCapturingGroups($regex) {
        if (false === strpos($regex, '(')) {
            // Needs to have at least a ( to contain a capturing group
            return false;
        }

        // Semi-accurate detection for capturing groups
        return preg_match(
            '~
                (?:
                    \(\?\(
                  | \[ [^\]\\\\]* (?: \\\\ . [^\]\\\\]* )* \]
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

    /**
     * @param $path
     * @return array
     * @throws \Exception
     */
    public function parseRoute($path)
    {
        $segments = preg_split('~' . self::VARIABLE_REGEX . '(*SKIP)(*F) | \[~x', $path);

        $currentRoute = '';
        $routeInfo = [];
        foreach ($segments as $n => $segment) {
            if ($segment === '' && $n !== 0) {
                throw new \Exception("Empty optional part");
            }

            $currentRoute .= $segment;
            $routeInfo[] = $this->parsePlaceholders($currentRoute);
        }

        unset($currentRoute, $segments);

        return $this->buildRouteRegex($routeInfo[0]);
    }

    /**
     * @param $route
     * @return array
     */
    private function parsePlaceholders($route) {
        if (!preg_match_all(
            '~' . self::VARIABLE_REGEX . '~x', $route, $matches,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER
        )
        ) {
            return [$route];
        }

        $offset = 0;
        $routeInfo = [];
        foreach ($matches as $set) {
            if ($set[0][1] > $offset) {
                $routeInfo[] = substr($route, $offset, $set[0][1] - $offset);
            }
            $routeInfo[] = [
                $set[1][0],
                isset($set[2]) ? trim($set[2][0]) : self::DEFAULT_DISPATCH_REGEX
            ];
            $offset = $set[0][1] + strlen($set[0][0]);
        }

        if ($offset != strlen($route)) {
            $routeInfo[] = substr($route, $offset);
        }

        return $routeInfo;
    }
}