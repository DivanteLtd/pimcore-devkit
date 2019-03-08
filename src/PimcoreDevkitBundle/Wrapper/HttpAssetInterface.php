<?php
/**
 * @date        08/03/2019
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 DIVANTE (http://divante.co)
 */

namespace PimcoreDevkitBundle\PimcoreDevkitBundle\Wrapper;

use GuzzleHttp\ClientInterface;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Folder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Interface HttpAssetInterface
 * @package PimcoreDevkitBundle\PimcoreDevkitBundle\Wrapper
 */
interface HttpAssetInterface
{
    /**
     * @return string
     */
    public function getFileType(): string;

    /**
     * @return string
     */
    public function getContentType(): string;

    /**
     * @return Asset
     * @throws \Exception
     */
    public function getAsset(): Asset;
}
