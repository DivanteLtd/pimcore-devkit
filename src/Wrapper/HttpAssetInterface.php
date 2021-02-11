<?php
/**
 * @author      Wojciech Peisert <wojtekp@sauron.pl>
 */

namespace PimcoreDevkitBundle\Wrapper;

use GuzzleHttp\ClientInterface;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Folder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Interface HttpAssetInterface
 * @package PimcoreDevkitBundle\Wrapper
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
