<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        12/12/2017
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class AbstractService
 * @package PimcoreDevkitBundle\Service
 */
abstract class AbstractService implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param string $name
     * @return string
     */
    public function locateFile(string $name) : string
    {
        $locator = $this->container->get('file_locator');
        return $locator->locate($name);
    }

    /**
     * @param string $filePath
     * @return array
     */
    public function getDataFromFile(string $filePath) : array
    {
        return include $filePath;
    }
}
