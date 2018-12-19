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
use MongoDB\GridFS\Exception\FileNotFoundException;
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
        $key    = Asset\Service::getValidKey($key, 'folder');
        $path = $parent->getRealFullPath() . '/' . $key;

        $folder = Folder::getByPath($path);
        if (!$folder instanceof Folder) {
            $folder = Folder::create(
                $parentId,
                [
                    'parentId'         => $parentId,
                    'creationDate'     => time(),
                    'userOwner'        => 0,
                    'userModification' => 0,
                    'path'             => $key,
                    'published'        => true,
                    'type'             => 'folder',
                    'locked'           => true,
                    'filename'         => $key,
                ]
            );
        }

        return $folder;
    }

    /**
     * @param string $url
     * @param Folder $targetFolder
     * @param null $filename
     * @return Asset
     * @throws \Exception
     */
    public function getHttpAsset(string $url, Folder $targetFolder, $filename = null): Asset
    {
        if (!$this->httpClient) {
            $this->httpClient = \Pimcore::getContainer()->get('pimcore.http_client');
        }

        $res = $this->httpClient->request('GET', $url, ['timeout' => 5]);
        $statusCode = $res->getStatusCode();

        if ($statusCode != 200) {
            throw new FileNotFoundException();
        }
        $type = $res->getHeader('content-type')[0];

        $fileType = array_search($type, Tool\Mime::$extensionMapping);
        if (false === $fileType) {
            $fileType = 'txt';
        }

        if (!$filename) {
            $filename = md5((string) $res->getBody()) . "." . $fileType;
        }

        $asset = Asset::getByPath($targetFolder->getFullPath() . "/" . $filename);

        if (!$asset) {
            $asset = new Asset();
            $asset->setFilename($filename);
            $asset->setParent($targetFolder);
            $asset->addMetadata("origin", "text", "url");
            $asset->addMetadata("origin_url", "text", $url);
            $asset->setData($res->getBody());
            $asset->save();
        }

        return $asset;
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
            throw new \Symfony\Component\Filesystem\Exception\FileNotFoundException(
                "Asset with path ". $targetFolder ."/". $key ." was not found"
            );
        }

        return $asset;
    }
}
