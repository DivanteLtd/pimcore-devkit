<?php

/**
 * @date        19/11/2018
 * @author      Michał Filik <mfilik@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service\Migration;

use PimcoreDevkitBundle\Service\MigrationService;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration as BaseMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpKernel\Config\FileLocator;

/**
 * Class AbstractPimcoreMigration
 * @package BookdataBundle\Migrations
 */
abstract class AbstractPimcoreMigration extends BaseMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var \ReflectionClass
     */
    private $reflector;

    /**
     * @return MigrationService
     */
    protected function getMigrationService(): MigrationService
    {
        return $this->container->get(MigrationService::class);
    }

    /**
     * @return FileLocator
     */
    protected function getFileLocator(): FileLocator
    {
        return $this->container->get('file_locator');
    }

    /**
     * @return array|string
     * @throws \Exception
     */
    protected function getVersionDirectory()
    {
        return $this->getFileLocator()->locate(sprintf(
            '@%s/Resources/migrations/%s/',
            $this->getFullBundleName(),
            $this->getReflector()->getShortName()
        ));
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getFullBundleName()
    {
        $namespaceParts = explode('\\', $this->getReflector()->getNamespaceName());

        if (count($namespaceParts) < 1) {
            throw new \Exception(
                sprintf("Missing namespace for class %s.", $this->getReflector()->getName())
            );
        }

        return $namespaceParts[0];
    }

    /**
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    private function getReflector()
    {
        if (null === $this->reflector) {
            $this->reflector = new \ReflectionClass($this);
        }
        return $this->reflector;
    }
}
