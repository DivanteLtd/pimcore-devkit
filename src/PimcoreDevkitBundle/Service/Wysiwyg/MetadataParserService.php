<?php

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service\Wysiwyg;

/**
 * Class MetadataParserService
 * @package PimcoreDevkitBundle\Service\Wysiwyg
 */
class MetadataParserService
{
    /**
     * @param array $metadata
     * @param string $name
     * @param string $lang
     * @param bool $langExact
     * @return string|null
     *
     * $metadata is an array of items that are array of the form:
     * [
     *    'name' => "alt",
     *    'language => "en_GB",
     *    'type' => 'input',
     *    'data' => 'profile photo of a man'
     * ]
     *
     * $langExact
     *      - if true, field 'laguage' must be equal to $lang;
     *      - if false, we match beginning, like $lang='en' matches metadata with language: 'en_GB'
     */
    public function getValue(array $metadata, string $name, string $lang, bool $langExact): ?string
    {
        foreach ($metadata as $item) {
            if ($name != $item['name']) {
                continue;
            }
            $language = $item['language'];
            if (($language === $lang) || $lang && (strpos($language, $lang) === 0) && !$langExact) {
                return $item['data'];
            }
        }

        return null;
    }

    /**
     * @param array $metadata
     * @param string $name
     * @param string $lang
     * @return string|null
     *
     * $metadata is an array of items that are array of the form:
     * [
     *    'name' => "alt",
     *    'language => "en_GB",
     *    'type' => 'input',
     *    'data' => 'profile photo of a man'
     * ]
     *
     * It tries to get value for matching language (i. e. 'en' matches 'en_GB',
     * if not found, it takes value for no language.
     */
    public function getMatchingValue(array $metadata, string $name, string $lang): ?string
    {
        if ($lang) {
            $value = $this->getValue($metadata, $name, $lang, false);
        }
        if (null === $value) {
            $value = $this->getValue($metadata, $name, '', true);
        }

        return $value;
    }
}
