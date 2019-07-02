<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        14/02/2018 11:53
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\Asset\Image\Thumbnail\Config as ThumbnailConfig;

/**
 * Class ThumbnailConfigService
 * @package PimcoreDevkitBundle\Service
 */
class ThumbnailConfigService
{
/* EXAMPLE CONFIG
[
    'name'     => 'articleThumbnail',
    'settings' =>
        [
            'description'    => '',
            'quality'        => 85,
            'highResolution' => 0,
        ],
    'mediaData' =>
        [
            'default' =>
                [
                    [
                        'type'      => 'resize',
                        'arguments' =>
                            [
                                'width'          => 262,
                                'height'         => 200
                            ]
                    ]
                ]
        ]
];
*/
    /**
     * Create or update (by name) thumbnail config
     *
     * @param array $config
     * @return ThumbnailConfig|null
     */
    public function setThumbnailConfigByName(array $config)
    {
        $name = $config['name'];
        $thumbConfig = ThumbnailConfig::getByName($name);
        if (false === $thumbConfig instanceof ThumbnailConfig) {
            $thumbConfig = new ThumbnailConfig();
        }

        $thumbConfig->setName($name);

        $settings = $config['settings'];
        if (is_array($settings)) {
            foreach ($settings as $name => $value) {
                $this->tryAssignSetting($thumbConfig, $name, $value);
            }
        }

        $thumbConfig->resetItems();
        $mediaData = $config['mediaData'];
        if (is_array($mediaData)) {
            foreach ($mediaData as $mediaName => $items) {
                $this->addItems($thumbConfig, $mediaName, $items);
            }
        }

        $thumbConfig->save();

        return $thumbConfig;
    }

    /**
     * Silently delete thumbnail config by name
     *
     * @param string $name
     * @return ThumbnailConfig
     * @return void
     */
    public function deleteThumbnailConfigByName(string $name)
    {
        $name = $config['name'];
        $thumbConfig = ThumbnailConfig::getByName($name);
        if (false !== $thumbConfig instanceof ThumbnailConfig) {
            $thumbConfig->delete();
        }
    }

    /**
     * Assigns setting if exists
     *
     * @param ThumbnailConfig $thumbConfig
     * @param string $name
     * @param string $value
     * @return void
     */
    private function tryAssignSetting(ThumbnailConfig $thumbConfig, $name, $value): void
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($thumbConfig, $setter)) {
            $thumbConfig->$setter($value);
        }
    }

    /**
     * Add items to thumbnail config
     *
     * @param ThumbnailConfig $thumbConfig
     * @param string $mediaName
     * @param array $items
     * @return void
     */
    private function addItems(ThumbnailConfig $thumbConfig, string $mediaName, array $items): void
    {
        if (is_array($items)) {
            foreach ($items as $item) {
                $this->addItem($thumbConfig, $mediaName, $item);
            }
        }
    }

    /**
     * Add item to thumbnail config
     *
     * @param ThumbnailConfig $thumbConfig
     * @param string $mediaName
     * @param array $item
     * @return void
     */
    private function addItem(ThumbnailConfig $thumbConfig, string $mediaName, array $item): void
    {
        $thumbConfig->addItem($item['type'], $item['arguments'], $mediaName);
    }
}
