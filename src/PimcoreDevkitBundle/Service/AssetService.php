<?php
/**
 * @date        29/01/2018
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2018 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\Asset\Folder;
use Pimcore\Model\Asset;

/**
 * Class AssetService
 *
 * @package PimcoreDevkitBundle\Service
 */
class AssetService
{
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
}
