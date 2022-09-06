<?php
/**
 * @category    Pimcore DevKit
 * @date        19/06/2019
 * @author      Michał Bolka <mbolka@divante.co>
 * @copyright   Copyright (c) 2019 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\Command;

use PimcoreDevkitBundle\FileLocator\PimcoreBrickLocator;
use PimcoreDevkitBundle\Service\InstallerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BricksUpdateCommand
 * @package ClassInstallerBundle\Command
 */
class BricksUpdateCommand extends Command
{
    /** @var string $defaultName */
    protected static $defaultName = 'devkit:bricks:update';

    /** @var PimcoreBrickLocator $locator */
    private $locator;

    /** @var InstallerService $installerService */
    private $installerService;

    /**
     * ClassesUpdateCommand constructor.
     * @param PimcoreBrickLocator $locator
     * @param InstallerService $installerService
     * @param string|null $name
     */
    public function __construct(PimcoreBrickLocator $locator, InstallerService $installerService, ?string $name = null)
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
            ->setDescription('Updates bricks from one (if specified) or all (by default) bundles under src catalog')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'Bundle name');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $bundleName = $input->getArgument('bundle');

        $brickFiles = $this->locator->getFiles($bundleName);

        if (!$brickFiles) {
            $inputOutput->caution('No bricks were found!');
        }

        foreach ($brickFiles as $brickName => $brickFile) {
            $this->installerService->createObjectBrickDefinition($brickName, $brickFile);
            $inputOutput->success(sprintf('Brick %s was successfully installed!', $brickName));
        }

        return Command::SUCCESS;
    }
}
