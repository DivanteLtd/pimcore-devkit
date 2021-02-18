<?php

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

/**
 * Class DomService
 * @package PimcoreDevkitBundle\Service
 */
class DomService
{
    /**
     * @param string $html
     * @return \DOMDocument
     */
    public function loadHTML(string $html): \DOMDocument
    {
        $doc = new \DOMDocument();
        $doc->loadHTML(
            mb_convert_encoding("<div>$html</div>", 'HTML-ENTITIES', 'UTF-8')
        );

        $container = $doc->getElementsByTagName('div')->item(0);
        $container = $container->parentNode->removeChild($container);
        while ($doc->firstChild) {
            $doc->removeChild($doc->firstChild);
        }

        while ($container->firstChild)
        {
            $doc->appendChild($container->firstChild);
        }

        return $doc;
    }
}
