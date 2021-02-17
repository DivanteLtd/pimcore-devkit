<?php

namespace PimcoreDevkitBundle\Command;

use PimcoreDevkitBundle\FileLocator\PimcoreFieldcollectionLocator;
use PimcoreDevkitBundle\Service\InstallerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FieldcollectionsUpdateCommand extends Command
{
    protected static $defaultName = 'devkit:fieldcollection:update';

    /** @var PimcoreFieldcollectionLocator  */
    private $locator;

    /** @var InstallerService  */
    private $installerService;

    /**
     * @param PimcoreFieldcollectionLocator $locator
     * @param InstallerService $installerService
     * @param string|null $name
     */
    public function __construct(PimcoreFieldcollectionLocator $locator, InstallerService $installerService, ?string $name = null)
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
            ->setDescription('Updates fieldcollections from one (if specified) or all (by default) bundles under src catalog')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'Bundle name');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $bundleName = $input->getArgument('bundle');

        $fieldcollectionFiles = $this->locator->getFiles($bundleName);
        if (!$fieldcollectionFiles) {
            $inputOutput->caution('No fieldcollection were found!');
        }
        foreach ($fieldcollectionFiles as $fieldcollectionName => $fieldcollectionFile) {
            $this->installerService->createFieldcollectionDefinition($fieldcollectionName, $fieldcollectionFile);
            $inputOutput->success(sprintf('Fieldcollection %s was successfully installed!', $fieldcollectionName));
        }
    }
}
