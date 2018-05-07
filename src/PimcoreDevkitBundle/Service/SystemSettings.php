<?php
/**
 * @date        5/12/2017
 *
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Config;
use Pimcore\File;

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
        $existingConfig = Config::getSystemConfig();
        $existingValues = $existingConfig->toArray();

        return $existingValues;
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings)
    {
        $configFile = \Pimcore\Config::locateConfigFile('system.php');
        File::putPhpFile($configFile, to_php_data_file_format($settings));
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
