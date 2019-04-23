<?php
/**
 * @category    Pimcore Plugin
 * @date        23/04/2019 12:26
 * @author      Jakub Płaskonka <jplaskonka@divante.pl>
 * @copyright   Copyright (c) 2017 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\Command;

use Pimcore\Console\AbstractCommand;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\Folder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SynchronizeAssetsCommand
 * @package PimcoreDevkitBundle\Command
 */
class SynchronizeAssetsCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure() : void
    {
        $helpMessage =  "This command allows you to synchronize assets tree with filesystem changes.".PHP_EOL;
        $helpMessage .= "Example usage:".PHP_EOL;
        $helpMessage .= "bin/console devkit:asset:synchronize";

        $this
            ->setName("devkit:asset:synchronize")
            ->setDescription("Synchronizes assets tree with filesystem changes")
            ->setHelp($helpMessage)
            ->addArgument("folder", InputOption::VALUE_OPTIONAL, "Tree subfolder; e.g. /Images");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $folder = $input->getArgument("folder");

        if (!$folder) {
            $folder = "/";
        } else {
            $folder = $folder[0];
        }
        $files = $this->listFolderFiles(PIMCORE_ASSET_DIRECTORY.$folder);

        foreach ($files as $file) {
            // Is it a directory?
            if (is_dir($file)) {
                continue;
            }

            $parentPath = substr( dirname($file), strlen(PIMCORE_ASSET_DIRECTORY)). "/";
            $filename = basename($file);

            // Does asset exist?
            $asset = Asset::getByPath($parentPath.$filename);
            if ($asset) {
                continue;
            }

            $asset = new Asset();
            $asset->setParent(Asset\Service::createFolderByPath($parentPath));
            $asset->setFilename($filename);
            $asset->setData(file_get_contents($file));
            try {
                $asset->save();
                $output->writeln("Saved ".$parentPath.$filename." as ".$asset->getType());
            } catch (\Throwable $exception) {
                $output->writeln("Couldn't save ".$parentPath.$filename);
                if ($output->isVerbose()) {
                    $output->writeln($exception->getMessage());
                    $output->writeln($exception->getTraceAsString());
                }
            }
        }
    }

    /**
     * @param $dir
     * @return array
     */
    function listFolderFiles($dir)
    {
        $ffs = rscandir($dir);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        return $ffs;
    }
}
