<?php
/**
 * @category    Pimcore DevKit
 * @date        17/06/2019
 * @author      Michał Bolka <mbolka@divante.pl>
 * @copyright   Copyright (c) 2019 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\FileLocator;

use Pimcore\Extension\Bundle\PimcoreBundleManager;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

/**
 * Class PimcoreBundlesFilesLocator
 * @package PimcoreDevkitBundle\FileLocator
 */
abstract class PimcoreBundlesFilesLocator
{
    const SUBCATALOG = '';
    const FILE_REGEX = '';

    /** @var PimcoreBundleManager $bundleManager */
    private $bundleManager;

    /** @var Finder $finder */
    private $finder;

    /**
     * ClassFilesLocator constructor.
     * @param PimcoreBundleManager $bundleManager
     * @param Finder $finder
     */
    public function __construct(PimcoreBundleManager $bundleManager, Finder $finder)
    {
        $this->bundleManager = $bundleManager;
        $this->finder = $finder;
    }

    /**
     * @param string|null $bundleName
     * @return array
     */
    public function getFiles($bundleName = null)
    {
        if (null !== $bundleName) {
            $catalogs = [$bundleName];
        } else {
            $catalogs = $this->getBundleCatalogList();
        }
        $classes = [];
        foreach ($catalogs as $catalog) {
            $classes = array_merge($classes, $this->getPimcoreFiles($catalog));
        }
        return $classes;
    }

    /**
     * @return array
     */
    private function getBundleCatalogList()
    {
        $bundles = ['AppBundle'];
        foreach ($this->bundleManager->getEnabledBundleNames() as $fullBundleClassName) {
            $bundles[] = explode('\\', $fullBundleClassName)[0];
        }
        return $bundles;
    }

    /**
     * @param string $bundleCatalog
     * @return array
     */
    private function getPimcoreFiles(string $bundleCatalog): array
    {
        $finder = $this->getFinder();
        try {
            $finder->in(sprintf('src/%s/%s', $bundleCatalog, static::SUBCATALOG));
        } catch (DirectoryNotFoundException $exception) {
        }
        try {
            $finder->in(sprintf('app/%s', static::SUBCATALOG));
        } catch (DirectoryNotFoundException $exception) {
        }

        if (!$finder->hasResults()) {
            return [];
        }
        $files = [];
        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $fileName = $file->getRelativePathname();
            $className = $this->getClassName($fileName);
            if ($className) {
                $files[$className] = $file->getRealPath();
            }
        }
        return $files;
    }

    /**
     * @param string $fileName
     * @return mixed|null
     */
    private function getClassName(string $fileName)
    {
        preg_match(static::FILE_REGEX, $fileName, $match);
        return $match ? $match[1] : null;
    }

    /**
     * @return Finder
     */
    private function getFinder(): Finder
    {
        return $this->finder;
    }
}
