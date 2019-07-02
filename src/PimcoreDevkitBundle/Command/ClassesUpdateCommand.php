<?php
/**
 * @category    Pimcore DevKit
 * @date        17/06/2019
 * @author      Michał Bolka <mbolka@divante.pl>
 * @copyright   Copyright (c) 2019 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\Command;

use PimcoreDevkitBundle\FileLocator\PimcoreClassLocator;
use PimcoreDevkitBundle\Service\InstallerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ClassesUpdateCommand
 * @package ClassInstallerBundle\Command
 */
class ClassesUpdateCommand extends Command
{
    protected static $defaultName = 'devkit:classes:update';

    /** @var PimcoreClassLocator $locator */
    private $locator;

    /** @var InstallerService $installerService */
    private $installerService;

    /**
     * ClassesUpdateCommand constructor.
     * @param PimcoreClassLocator $locator
     * @param InstallerService           $installerService
     * @param string|null                $name
     */
    public function __construct(PimcoreClassLocator $locator, InstallerService $installerService, ?string $name = null)
    {
        parent::__construct($name);
        $this->locator = $locator;
        $this->installerService = $installerService;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('Updates classes from one (if specified) or all (by default) bundles under src catalog')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'Bundle name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $bundleName = $input->getArgument('bundle');

        $classFiles = $this->locator->getFiles($bundleName);

        if (!$classFiles) {
            $inputOutput->caution('No classes were found!');
        }

        foreach ($classFiles as $className => $classFile) {
            $this->installerService->createClassDefinition($className, $classFile);
            $inputOutput->success(sprintf('Class %s was successfully installed!', $className));
        }
    }
}
