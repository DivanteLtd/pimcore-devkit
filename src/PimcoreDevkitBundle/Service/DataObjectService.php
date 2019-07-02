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
    const DELETE_BATCH_SIZE = 1000;

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

    /**
     * @param string $class
     * @param array $searchData
     * @param string $objectName
     * @param string $objectFolder
     * @return DataObject|AbstractObject
     */
    public function getOrCreateDataObject(
        $class,
        $searchData,
        $objectName,
        $objectFolder
    )
    {
        $object = $this->findDataObject(
            $class,
            $searchData
        );

        if (!$object instanceof $class) {
            $object = $this->createDataObject(
                $class,
                $objectName,
                $objectFolder,
                $searchData
            );
        }

        return $object;
    }

    /**
     * @param string $class
     * @param array $searchData
     * @return DataObject
     */
    public function findDataObject(
        $class,
        $searchData
    )
    {
        $listingClass = $class . '\Listing';
        /** @var DataObject\Listing $listing */
        $listing = new $listingClass();
        $conditions = [];
        $data = [];
        foreach ($searchData as $name => $value) {
            $conditions[] = "$name = ?";
            $data[] = $value;
        }
        $conditions = implode(' AND ', $conditions);

        $listing->setCondition($conditions, $data);
        $listing->setUnpublished(true);
        $objects = $listing->getItems(0, 1);

        $object = $objects[0];

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
            'key'                => $key,
            'parent'             => $objectFolder,
            'o_published'        => false,
            'o_creationDate'     => time(),
            'o_userOwner'        => 0,
            'o_userModification' => 0,
            'o_index'            => 0,
        ];
        $object = $class::create(array_merge($data, $additionalData));
        $object->save();

        return $object;
    }

    /**
     * @param string $class
     * @return \Generator|string
     * @throws \Exception
     */
    public function removeAllObjects(string $class)
    {
        if (!class_exists($class)) {
            throw new \Exception("There is no class $class");
        }

        $removedCount = 0;

        $skipIds = [];

        while (true) {
            $listingClass = $class . "\\Listing";
            $listing = new $listingClass();
            $listing->setUnpublished(true);
            $listing->setLimit(self::DELETE_BATCH_SIZE);
            if (count($skipIds) > 0) {
                $listing->setCondition(" o_id NOT IN ('" . implode("','", $skipIds) . "')");
            }
            $listing->load();
            if (0 === $listing->getCount()) {
                break;
            }

            foreach ($listing->getObjects() as $object) {
                $message = $object->getFullPath();
                try {
                    $object->delete();
                    $removedCount++;
                    $message .= " - deleted";
                } catch (\Exception $e) {
                    $skipIds[] = $object->getId();
                    $message .= " - delete ERROR (skipped)";
                }
                yield $message;
            }
        }

        return strval($removedCount);
    }
}
