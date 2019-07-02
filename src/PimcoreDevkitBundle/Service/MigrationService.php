<?php
/**
 * @date        19/11/2018
 * @author      Michał Filik <mfilik@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Extension\Bundle\Installer\Exception\InstallationException;
use Pimcore\Log\Simple;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\Fieldcollection;
use Pimcore\Model\Translation\Admin as AdminTranslation;
use Pimcore\Model\Translation\Website;
use Pimcore\Model\Translation\Website as WebsiteTranslation;

/**
 * Class MigrationService
 *
 * @package PimcoreDevkitBundle\Service
 */
class MigrationService
{
    const LOG_FILE = "log";

    /**
     * @param array $translations
     *
     * @return bool
     * @throws \Exception
     */
    public function addAdminTranslations(array $translations): bool
    {
        $areAllSaved = true;
        foreach ($translations as $key => $data) {
            $translation = AdminTranslation::getByKey($key, true);

            $translation->addTranslation('en', $data['en']);
            $translation->addTranslation('pl', $data['pl']);

            $translation->setModificationDate(time());
            try {
                $translation->save();
            } catch (\Exception $exception) {
                $areAllSaved = false;
                Simple::log(self::LOG_FILE, $exception);
            }
        }

        return $areAllSaved;
    }

    /**
     * @param array $translations
     *
     * @return bool
     * @throws \Exception
     */
    public function addSharedTranslation(array $translations): bool
    {
        $areAllSaved = true;
        foreach ($translations as $key => $data) {
            $translation = Website::getByKey($key, true);

            $translation->addTranslation('en', $data['en']);
            $translation->addTranslation('pl', $data['pl']);

            $translation->setModificationDate(time());
            try {
                $translation->save();
            } catch (\Exception $exception) {
                $areAllSaved = false;
                Simple::log(self::LOG_FILE, $exception);
            }
        }

        return $areAllSaved;
    }

    /**
     * @param array $translations
     *
     * @return bool
     * @throws \Exception
     */
    public function removeAdminTranslations(array $translations): bool
    {
        $removed = true;
        foreach ($translations as $key => $data) {
            $translation = AdminTranslation::getByKey($key);

            if ($translation) {
                $translation->delete();
            } else {
                $removed = false;
            }
        }

        return $removed;
    }

    /**
     * @param string $name
     *
     * @return ClassDefinition
     */
    public function getOrCreateClass(string $name): ClassDefinition
    {
        try {
            $class = ClassDefinition::getByName($name);
        } catch (\Exception $exception) {
            $class = null;
            Simple::log(self::LOG_FILE, $exception);
        }
        if (null === $class) {
            $class = new ClassDefinition();
            $class->setName($name);
        }

        return $class;
    }

    /**
     * @param string          $filePath
     * @param ClassDefinition $class
     *
     * @return void
     */
    public function updateClass(string $filePath, ClassDefinition $class): void
    {
        try {
            $json = file_get_contents($filePath);
            ClassDefinition\Service::importClassDefinitionFromJson($class, $json, true, true);
        } catch (\Exception $exception) {
            throw new InstallationException(
                'An error occurred while installing ' . $class->getName(),
                0,
                $exception
            );
        }
    }

    /**
     * @param string $className
     * @param string $logFile
     *
     * @return void
     */
    public function removeClass(string $className, string $logFile): void
    {
        try {
            /** @var ClassDefinition $class */
            $class = ClassDefinition::getByName($className);
            if ($class) {
                $class->delete();
            }
        } catch (\Exception $ex) {
            Simple::log($logFile, $ex);
        }
    }

    /**
     * @param string $key
     * @return Fieldcollection\Definition
     */
    public function getOrCreateFieldCollection(string $key): Fieldcollection\Definition
    {
        try {
            $fc = Fieldcollection\Definition::getByKey($key);
        } catch (\Exception $ex) {
            $fc = null;
            Simple::log(self::LOG_FILE, $ex);
        }

        if (!$fc instanceof Fieldcollection\Definition) {
            $fc = new Fieldcollection\Definition();
            $fc->setKey($key);
        }

        return $fc;
    }

    /**
     * @param string $filePath
     * @param Fieldcollection\Definition $fc
     * @return void
     */
    public function updateFieldCollection(string $filePath, Fieldcollection\Definition $fc): void
    {
        try {
            $json = file_get_contents($filePath);
            ClassDefinition\Service::importFieldCollectionFromJson($fc, $json, true);
        } catch (\Exception $ex) {
            throw new InstallationException(
                'An error occurred while installing field collection: ' . $fc->getKey(),
                0,
                $ex
            );
        }
    }

    /**
     * @param array $translations
     *
     * @return bool
     * @throws \Exception
     */
    public function addWebsiteTranslations(array $translations): bool
    {
        $areAllSaved = true;
        foreach ($translations as $key => $data) {
            $translation = WebsiteTranslation::getByKey($key, true);

            $translation->addTranslation('en', $data['en']);
            $translation->addTranslation('pl', $data['pl']);

            $translation->setModificationDate(time());
            try {
                $translation->save();
            } catch (\Exception $exception) {
                $areAllSaved = false;
                Simple::log(self::LOG_FILE, $exception);
            }
        }

        return $areAllSaved;
    }

    /**
     * @param array $translations
     *
     * @return bool
     * @throws \Exception
     */
    public function removeWebsiteTranslations(array $translations): bool
    {
        $removed = true;
        foreach ($translations as $key => $data) {
            $translation = WebsiteTranslation::getByKey($key);

            if ($translation) {
                $translation->delete();
            } else {
                $removed = false;
            }
        }

        return $removed;
    }
}
