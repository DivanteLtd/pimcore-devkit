<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        23/01/2018
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2018 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\Document\Folder;
use Pimcore\Model\Document;
use Pimcore\Model\Document\Page;
use Pimcore\Model\Document\DocType;
use Pimcore\Model\Document\Snippet;

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

    /**
     * Creates snippet from doctype and returns it.
     *
     * @param int $parentId
     * @param string $key
     * @param string $docTypeName
     * @return Snippet
     * @throws \Exception
     */
    public function createSnippetFromDocType(int $parentId, string $key, string $docTypeName): Snippet
    {
        $docTypeService = new DocTypeService();

        $docType = $docTypeService->getDocTypeByName($docTypeName);
        if (false === $docType instanceof DocType) {
            throw new \Exception("Missing document type: '$docTypeName'");
        }

        $snippet = new Snippet();

        $snippet->setParentId($parentId);
        $snippet->setKey($key);
        $snippet->setController($docType->getController());
        $snippet->setAction($docType->getAction());
        $snippet->setTemplate($docType->getTemplate());
        $snippet->setModule($docType->getModule());

        $snippet->setPublished(true);
        $snippet->setCreationDate(time());
        $snippet->setModificationDate(time());
        $snippet->setUserOwner(0);
        $snippet->setUserModification(0);

        $snippet->save();

        return $snippet;
    }

    /**
     * Creates page from doctype and returns it.
     *
     * @param int $parentId
     * @param string $key
     * @param string $docTypeName
     * @return Page
     * @throws \Exception
     */
    public function createPageFromDocType(int $parentId, string $key, string $docTypeName): Page
    {
        $docTypeService = new DocTypeService();

        $docType = $docTypeService->getDocTypeByName($docTypeName);
        if (false === $docType instanceof DocType) {
            throw new \Exception("Missing document type: '$docTypeName'");
        }

        $doc = new Page();

        $doc->setParentId($parentId);
        $doc->setKey($key);
        $doc->setController($docType->getController());
        $doc->setAction($docType->getAction());
        $doc->setTemplate($docType->getTemplate());
        $doc->setModule($docType->getModule());

        $doc->setPublished(true);
        $doc->setCreationDate(time());
        $doc->setModificationDate(time());
        $doc->setUserOwner(0);
        $doc->setUserModification(0);

        $doc->save();

        return $doc;
    }
}
