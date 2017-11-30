<?php
/**
 * @date        30/11/2017
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Folder;

/**
 * Class DataObjectService
 *
 * @package PimcoreDevkitBundle\Service
 */
class DataObjectService
{
    /**
     * Returns Object folder. If not existent, creates it.
     *
     * @param int $parentId
     * @param string $key
     * @return Folder
     * @internal param string $name
     */
    public function getOrCreateObjectFolder($parentId, $key)
    {
        $parent = DataObject::getById($parentId);
        $key    = DataObject\Service::getValidKey($key, 'object');
        $path   = $parent->getRealFullPath() . '/' . $key;

        $folder = Folder::getByPath($path);
        if (!$folder instanceof Folder) {
            $folder = Folder::create([
                'o_parentId'         => $parentId,
                'o_creationDate'     => time(),
                'o_userOwner'        => 0,
                'o_userModification' => 0,
                'o_key'              => $key,
                'o_published'        => true,
                'o_locked'           => true,
            ]);
        }

        return $folder;
    }

    public function getOrCreateDataObjectByField(
        $class,
        $fieldName,
        $fieldValue,
        $objectName,
        $objectFolder
    )
    {
        $funcName = 'getBy' . $fieldName;
        $object = $class::$funcName($fieldValue, 1);
        if (!$object instanceof $class) {
            $object = $this->createDataObject(
                $class,
                $objectName,
                $objectFolder,
                [
                    $fieldName => $fieldValue
                ]
            );
        }

        return $object;
    }

    /**
     * @param string $class
     * @param string $objectName
     * @param Folder $objectFolder
     * @param array $data
     * @return AbstractObject
     */
    public function createDataObject(
        string $class,
        string $objectName,
        Folder $objectFolder,
        array $data
    )
    {
        $keyService = new KeyService();
        $key = $keyService->getFreeDataObjectKey($objectFolder, $objectName);
        $additionalData = [
            'key'    => $key,
            'parent' => $objectFolder,
        ];
        $object = $class::create(array_merge($data, $additionalData));
        $object->save();

        return $object;
    }
}
