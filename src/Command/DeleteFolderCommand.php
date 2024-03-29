<?php
/**
 * @category    Pimcore Plugin
 * @date        09/09/2017 12:26
 * @author      Jakub Płaskonka <jplaskonka@divante.pl>
 * @copyright   Copyright (c) 2017 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Pimcore\Model\DataObject\Folder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteFolderCommand
 *
 * @package Divante\PimcoreDevkitBundle\Command
 */
class DeleteFolderCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $helpMessage = "This command allows you to delete folder in tree without hassle of blocking admin UI.";
        $helpMessage .= PHP_EOL;
        $helpMessage .= "Example usage:" . PHP_EOL;
        $helpMessage .= "bin/console devkit:deletefolder -t object -f /bigUglyFolder";

        $this
            ->setName("devkit:deletefolder")
            ->setDescription("Deletes folder from given tree")
            ->setHelp($helpMessage)
            ->addOption("type", "t", InputOption::VALUE_REQUIRED, "Tree type (object, document, asset)")
            ->addOption("folder", "f", InputOption::VALUE_REQUIRED, "Folder path");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getOption("type");
        if (!$type) {
            $output->writeln("Missing -t option.");
            return Command::FAILURE;
        }

        $path = $input->getOption("folder");
        if (!$path) {
            $output->writeln("Missing -f option.");
            return Command::FAILURE;
        }

        try {
            switch ($type) {
                case "object":
                    $this->deleteObject($path);
                    break;
                case "document":
                    $this->deleteDocument($path);
                    break;
                case "asset":
                    $this->deleteAssetFolder($path);
                    break;
                default:
                    $output->writeln("Incorrect tree type, allowed types: object, document, asset.");
                    return Command::FAILURE;
            }

            $output->writeln("Folder " . $path . " has been deleted.");
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }

        return Command::SUCCESS;
    }

    /**
     * @param string $path
     * @return void
     * @throws \Exception
     */
    protected function deleteObject(string $path)
    {
        $folder = Folder::getByPath($path);
        if (!$folder) {
            throw new \Exception("Object with path " . $path . " does not exist.");
        } else {
            $folder->delete();
        }
    }

    /**
     * @param string $path
     * @return void
     * @throws \Exception
     */
    protected function deleteDocument(string $path)
    {
        $folder = \Pimcore\Model\Document\Folder::getByPath($path);
        if (!$folder) {
            throw new \Exception("Document with path " . $path . " does not exist.");
        } else {
            $folder->delete();
        }
    }

    /**
     * @param string $path
     * @return void
     * @throws \Exception
     */
    protected function deleteAssetFolder(string $path)
    {
        $folder = \Pimcore\Model\Asset\Folder::getByPath($path);
        if (!$folder) {
            throw new \Exception("Asset folder with path " . $path . " does not exist.");
        } else {
            $folder->delete();
        }
    }
}
