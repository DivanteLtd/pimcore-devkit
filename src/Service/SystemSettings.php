<?php
/**
 * @date        5/12/2017
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Config;
use Pimcore\File;
use Symfony\Component\Yaml\Yaml;

/**
 * Class SystemSettings
 *
 * @package PimcoreDevkitBundle\Service
 */
class SystemSettings
{
    /**
     * @return array
     */
    public function getSettings()
    {
        $file = Config::locateConfigFile('system.yml');
        $existingValues = Config::getConfigInstance($file, true);

        return $existingValues;
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings)
    {
        $settingsYml = Yaml::dump($settings, 5);
        $configFile = Config::locateConfigFile('system.yml');
        File::put($configFile, $settingsYml);
    }

    /**
     * @param array $settings
     */
    public function updateSettings(array $settings)
    {
        $existingSettings = $this->getSettings();
        $settings = array_replace_recursive($existingSettings, $settings);

        $this->setSettings($settings);
    }
}
