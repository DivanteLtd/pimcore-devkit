<?php

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service\Wysiwyg;

use PimcoreDevkitBundle\Model\AssetSeoData;
use Pimcore\Model\Asset;

/**
 * Class AssetSeoService
 * @package PimcoreDevkitBundle\Service\Wysiwyg
 */
class AssetSeoService
{
    /**
     * @var MetadataParserService
     */
    private $metadataParser;

    /**
     * AssetSeoService constructor.
     * @param MetadataParserService $parserService
     */
    public function __construct(MetadataParserService $metadataParser)
    {
        $this->metadataParser = $metadataParser;
    }

    /**
     * @param Asset $asset
     * @param string $lang
     * @return AssetSeoData
     */
    public function getAssetSeoData(Asset $asset, string $lang): AssetSeoData
    {
        $arr = explode(',', $lang);
        $lang = $arr[0];

        $seoData = new AssetSeoData();
        $metadata = $asset->getMetadata();
        if (!is_array($metadata)) {
            return $seoData;
        }

        $title = (string)$this->metadataParser->getMatchingValue($metadata, 'title', $lang);
        $seoData->setTitle($title);
        $alt = (string)$this->metadataParser->getMatchingValue($metadata, 'alt', $lang);
        $seoData->setAlt($alt);

        return $seoData;
    }
}
