<?php
/**
 * @date        29/01/2018
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @author      Jakub Płaskonka <jplaskonka@divante.pl>
 * @copyright   Copyright (c) 2018 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use GuzzleHttp\ClientInterface;
use PimcoreDevkitBundle\PimcoreDevkitBundle\Wrapper\HttpAsset;
use PimcoreDevkitBundle\PimcoreDevkitBundle\Wrapper\HttpAssetInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Pimcore\Http\ClientFactory;
use Pimcore\Model\Asset\Folder;
use Pimcore\Model\Asset;
use Pimcore\Tool;

/**
 * Class AssetService
 *
 * @package PimcoreDevkitBundle\Service
 */
class AssetService
{
    public const DEFAULT_FILE_TYPE = 'txt';

    /** @var ClientInterface */
    protected $httpClient;

    /**
     * Returns asset folder. If not existent, creates it.
     *
     * @param int $parentId
     * @param string $key
     * @return Folder
     * @internal param string $name
     */
    public function getOrCreateAssetFolder($parentId, $key)
    {
        /** @var Asset $parent */
        $parent = Asset::getById($parentId);
        $key = Asset\Service::getValidKey($key, 'folder');
        $path = $parent->getRealFullPath() . '/' . $key;

        $folder = Folder::getByPath($path);
        if (!$folder instanceof Folder) {
            $folder = Folder::create(
                $parentId,
                [
                    'parentId' => $parentId,
                    'creationDate' => time(),
                    'userOwner' => 0,
                    'userModification' => 0,
                    'path' => $key,
                    'published' => true,
                    'type' => 'folder',
                    'locked' => true,
                    'filename' => $key,
                ]
            );
        }

        return $folder;
    }

    /**
     * @return ClientInterface
     */
    protected function getHttpClient(): ClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = \Pimcore::getContainer()->get('pimcore.http_client');
        }
        return $this->httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     */
    public function setHttpClient(ClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $url
     * @param Folder $targetFolder
     * @param null $filename
     * @param string $defaultFileType
     * @param array $headers Headers to append
     * @return HttpAssetInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function prepareHttpAsset(
        string $url,
        Folder $targetFolder,
        $filename = null,
        string $defaultFileType = self::DEFAULT_FILE_TYPE,
        array  $headers = []
    ): HttpAssetInterface {
        return new HttpAsset($this->getHttpClient(), $url, $targetFolder, $filename, $defaultFileType, $headers);
    }

    /**
     * @param string $url
     * @param Folder $targetFolder
     * @param null $filename
     * @param string $defaultFileType
     * @param array $headers Headers to append
     * @return Asset
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getHttpAsset(
        string $url,
        Folder $targetFolder,
        $filename = null,
        string $defaultFileType = self::DEFAULT_FILE_TYPE,
        array  $headers = []
    ): Asset {
        $httpAsset = $this->prepareHttpAsset($url, $targetFolder, $filename, $defaultFileType, $headers);

        return $httpAsset->getAsset();
    }

    /**
     * @param string $key
     * @param Folder $targetFolder
     * @return Asset
     */
    public function getAsset(string $key, Folder $targetFolder): Asset
    {
        $asset = Asset::getByPath($targetFolder . "/" . $key);

        if (!$asset) {
            throw new FileNotFoundException(
                "Asset with path " . $targetFolder . "/" . $key . " was not found"
            );
        }

        return $asset;
    }
}
