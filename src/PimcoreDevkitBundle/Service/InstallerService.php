<?php
/**
 * @date        02/11/2017
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\Folder;
use Pimcore\Model\WebsiteSetting;

/**
 * Class InstallerService
 *
 * @package PimcoreDevkitBundle\Service
 */
class InstallerService
{
    /**
     * @param string $wsName
     * @param int $parentId
     * @param string $key
     * @return Folder
     * @throws \Exception
     */
    public function createDataObjectFolderAndWebsiteSettings(string $wsName, int $parentId, string $key)
    {
        $setting = WebsiteSetting::getByName($wsName);

        if ($setting instanceof WebsiteSetting && $setting->getData()) {
            $folder = Folder::getById($setting->getData());
            if ($folder instanceof Folder) {
                return $folder;
            }
        }

        $dataObjectService = new DataObjectService();
        $folder = $dataObjectService->getOrCreateObjectFolder($parentId, $key);
        $this->setWebsiteSetting(
            [
                'name' => $wsName,
                'type' => 'object',
                'data' => $folder->getId(),
            ]
        );

        if (!$folder instanceof Folder) {
            throw new \Exception("Cannot get folder $key ");
        }

        return $folder;
    }

    /**
     * @param string $name
     * @param string $jsonFilePath
     * @return bool
     */
    public function createClassDefinition(string $name, string $jsonFilePath)
    {
        $class = null;
        try {
            $class = ClassDefinition::getByName($name);
        } catch (\Exception $e) {
            // ignore
        }
        if (false === $class instanceof ClassDefinition) {
            $class = ClassDefinition::create(['name' => $name, 'userOwner' => 0]);
        }

        $json = file_get_contents($jsonFilePath);
        $success = ClassDefinition\Service::importClassDefinitionFromJson($class, $json);

        return $success;
    }

    /**
     * Creates or updates WebsiteSettings.
     *
     * @param array $params
     * @return WebsiteSetting
     */
    public function setWebsiteSetting(array $params)
    {
        $setting = WebsiteSetting::getByName($params['name']);

        if (!$setting instanceof WebsiteSetting) {
            $setting = new WebsiteSetting();
        }

        $setting->setValues($params);
        $setting->save();

        return $setting;
    }

    /**
     * @param string $permission
     */
    public function createPermission(string $permission)
    {
        \Pimcore\Model\User\Permission\Definition::create($permission);
    }
}
