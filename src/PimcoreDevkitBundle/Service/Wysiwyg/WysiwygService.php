<?php

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service\Wysiwyg;

use PimcoreDevkitBundle\Model\AssetSeoData;
use Pimcore\Model\Asset;

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
     * Wysiwyg constructor.
     * @param AssetSeoService $assetSeoService
     * @param AssetFinderService $assetFinderService
     */
    public function __construct(AssetSeoService $assetSeoService, AssetFinderService $assetFinderService)
    {
        $this->assetSeoService    = $assetSeoService;
        $this->assetFinderService = $assetFinderService;
    }

    /**
     * @param string $html
     * @return string
     */
    public function addMetaToImages(string $html, string $lang = ''): string
    {
        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

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

        return $doc->saveHTML();
    }
}
