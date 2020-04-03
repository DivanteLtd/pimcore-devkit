<?php

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service\Wysiwyg;

use PimcoreDevkitBundle\Model\AssetSeoData;
use Pimcore\Model\Asset;
use PimcoreDevkitBundle\Service\DomService;

/**
 * Class WysiwygService
 * @package PimcoreDevkitBundle\Service\Wysiwyg
 */
class WysiwygService
{
    /**
     * @var AssetSeoService
     */
    private $assetSeoService;

    /**
     * @var AssetFinderService
     */
    private $assetFinderService;

    /**
     * @var DomService
     */
    private $domService;

    /**
     * @param AssetSeoService $assetSeoService
     * @param AssetFinderService $assetFinderService
     * @param DomService $domService
     */
    public function __construct(
        AssetSeoService $assetSeoService,
        AssetFinderService $assetFinderService,
        DomService $domService
    ) {
        $this->assetSeoService    = $assetSeoService;
        $this->assetFinderService = $assetFinderService;
        $this->domService         = $domService;
    }

    /**
     * @param string $html
     * @return string
     */
    public function addMetaToImages(string $html, string $lang = ''): string
    {
        if (!$html) {
            return '';
        }

        $doc = $this->domService->loadHTML($html);
        $doc = $this->addMetaToDomImages($doc, $lang);

        return $doc->saveHTML();
    }

    /**
     * @param \DOMDocument $doc
     * @param string $lang
     * @return \DOMDocument
     */
    public function addMetaToDomImages(\DOMDocument $doc, string $lang = ''): \DOMDocument
    {
        /** @var \DOMElement $imgNode */
        foreach ($doc->getElementsByTagName('img') as $imgNode) {
            $src = $imgNode->getAttribute('src');
            $asset = $this->assetFinderService->find($src);
            if (!$asset instanceof Asset) {
                continue;
            }
            $assetSeoData = $this->assetSeoService->getAssetSeoData($asset, $lang);

            if ($assetSeoData->getTitle()) {
                $imgNode->setAttribute('title', $assetSeoData->getTitle());
            }
            if ($assetSeoData->getAlt()) {
                $imgNode->setAttribute('alt', $assetSeoData->getAlt());
            }
        }

        return $doc;
    }
}
