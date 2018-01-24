<?php
/**
 * @date        23/01/2018
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2018 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\Document\Folder;
use Pimcore\Model\Document;

/**
 * Class DocumentService
 *
 * @package PimcoreDevkitBundle\Service
 */
class DocumentService
{
    /**
     * Returns document folder. If not existent, creates it.
     *
     * @param int $parentId
     * @param string $key
     * @return Folder
     * @internal param string $name
     */
    public function getOrCreateDocumentFolder($parentId, $key)
    {
        /** @var Document $parent */
        $parent = Document::getById($parentId);
        $key    = Document\Service::getValidKey($key, 'document');
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
                    'key'              => $key,
                    'published'        => true,
                    'type'             => 'folder',
                ]
            );
        }

        return $folder;
    }
}
