<?php

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service\Wysiwyg;

use Pimcore\Model\Asset;

/**
 * Class AssetFinderService
 * @package PimcoreDevkitBundle\Service\Wysiwyg
 */
class AssetFinderService
{
    private const PATTERNS = [
        [
            'pattern' => '#/(image|video)-thumb__([\d]+)__#',
            'pos' => 2,
        ],
        [
            'pattern' => '#/admin/asset/get-image-thumbnail\?id=([\d]+)#',
            'pos' => 1,
        ],
    ];

    /**
     * @var RegexService
     */
    private $regexService;

    /**
     * AssetFinderService constructor.
     * @param RegexService $regexService
     */
    public function __construct(RegexService $regexService)
    {
        $this->regexService = $regexService;
    }

    /**
     * @param string $src
     * @return Asset|null
     */
    public function find(string $src): ?Asset
    {
        $asset = $this->findByPath($src);
        if ($asset instanceof Asset) {
            return $asset;
        }

        $asset = $this->findByThumbnailUrl($src);
        if ($asset instanceof Asset) {
            return $asset;
        }

        return null;
    }

    /**
     * @param string $path
     * @return Asset|null
     */
    public function findByPath(string $path): ?Asset
    {
        try {
            $asset = Asset::getByPath($path);
            return $asset;
        } catch (\Exception $e) {
            // ignore
        }

        return null;
    }

    /**
     * E. g. socialmedia/image-thumb__15306__socialMedia-footerIcon/social-fb-circle.png
     * E. g. /admin/asset/get-image-thumbnail?id=22106&width=600&aspectratio=true
     *
     * @param string $url
     * @return Asset|null
     */
    public function findByThumbnailUrl(string $url): ?Asset
    {
        $idAsset = intval($this->regexService->searchByPatternsList($url, self::PATTERNS));
        if ($idAsset) {
            return Asset::getById($idAsset);
        }

        return null;
    }
}
