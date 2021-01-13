<?php
/**
 * @date        26/10/2017 11:53
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2017 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\User\Workspace\AbstractWorkspace;
use Pimcore\Model\Element;

/**
 * Class WorkspaceService
 *
 * @package PimcoreDevkitBundle\Service
 */
class WorkspaceService
{
    const ALL_PERMISSIONS = ["list","view","save","publish","unpublish",
        "delete","rename","create","settings","versions","properties"];

    /**
     * @param string $type 'object'|'document'|'asset'
     * @param string $path
     * @param array $permissions
     * @return null|AbstractWorkspace
     */
    public static function getWorkspace($type, $path, array $permissions = [])
    {
        if (count($permissions) == 0) {
            $permissions = self::getAllPossiblePermisionsFormatted();
        }

        $space = array_merge($permissions, ['path' => $path]);

        $element = Element\Service::getElementByPath($type, $path);
        if ($element) {
            $className = '\\Pimcore\\Model\\User\\Workspace\\' . Element\Service::getBaseClassNameForElement($type);
            /** @var AbstractWorkspace $workspace */
            $workspace = new $className();
            $workspace->setValues($space);

            $workspace->setCid($element->getId());
            $workspace->setCpath($element->getRealFullPath());

            return $workspace;
        }

        return null;
    }

    /**
     * @return array
     */
    private static function getAllPossiblePermisionsFormatted()
    {
        return array_fill_keys(self::ALL_PERMISSIONS, true);
    }
}
