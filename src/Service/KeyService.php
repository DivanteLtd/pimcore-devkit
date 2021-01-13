<?php
/**
 * @date        30/11/2017
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\File;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Folder;

/**
 * Class KeyService
 *
 * @package PimcoreDevkitBundle\Service
 */
class KeyService
{
    /**
     * @param Folder $objectFolder
     * @param string $objectName
     * @return string
     */
    public function getFreeDataObjectKey(Folder $objectFolder, $objectName)
    {
        $initialKey = File::getValidFilename($objectName);
        $folderPath = $objectFolder->getFullPath();
        $initialPath = $folderPath . '/' . $initialKey;

        if (!AbstractObject::getByPath($initialPath)) {
            return $initialKey;
        }

        for ($iter = 2; $iter <= 999; ++$iter) {
            $key = $initialKey . '_' . $iter;
            $path = $folderPath . '/' . $key;
            if (!AbstractObject::getByPath($path)) {
                return $key;
            }
        }

        return $initialKey;
    }
}
