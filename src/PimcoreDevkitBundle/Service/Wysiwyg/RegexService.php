<?php

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service\Wysiwyg;

/**
 * Class RegexService
 * @package PimcoreDevkitBundle\Service\Wysiwyg
 */
class RegexService
{
    /**
     * @param string $haystack
     * @param array $patterns
     * @return string|null
     */
    public function searchByPatternsList(string $haystack, array $patterns): ?string
    {
        foreach ($patterns as $pattern) {
            $needle = $this->searchByPattern($haystack, $pattern['pattern'], $pattern['pos']);
            if ($needle) {
                return $needle;
            }
        }

        return null;
    }

    /**
     * @param string $haystack
     * @param string $pattern
     * @param int $pos
     * @return string|null
     */
    public function searchByPattern(string $haystack, string $pattern, int $pos): ?string
    {
        $matches = [];
        preg_match($pattern, $haystack, $matches, PREG_OFFSET_CAPTURE);

        return isset($matches[$pos][0]) ? $matches[$pos][0] : null;
    }
}
