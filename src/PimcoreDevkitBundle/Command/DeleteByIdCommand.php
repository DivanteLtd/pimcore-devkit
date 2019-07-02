<?php
/**
 * @category    Pimcore Plugin
 * @date        20/12/2018 12:26
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\Command;

use Pimcore\Console\AbstractCommand;
use Pimcore\Model\Asset;
use Pimcore\Model\Document;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteByIdCommand
 *
 * @package Divante\PimcoreDevkitBundle\Command
 */
class DeleteByIdCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure() : void
    {
        $helpMessage =  "This command allows you to delete object, document, asset in tree without hassle of blocking admin UI.".PHP_EOL;
        $helpMessage .= "Example usage:".PHP_EOL;
        $helpMessage .= "bin/console devkit:delete_by_id -t object -id 1234";

        $this
            ->setName("devkit:delete_by_id")
            ->setDescription("Deletes object, document, asset from given tree")
            ->setHelp($helpMessage)
            ->addOption("type", "t", InputOption::VALUE_REQUIRED, "Tree type (object, document, asset)")
            ->addOption("id", "id", InputOption::VALUE_REQUIRED, "ID");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $type = $input->getOption("type");
        if(!$type) {
            $output->writeln("Missing -t option.");
            return;
        }

        $id = $input->getOption("id");
        if(!$id) {
            $output->writeln("Missing -id option.");
            return;
        }

        try {
            switch ($type) {
                case "object":
                    $this->deleteObject($id);
                    break;
                case "document":
                    $this->deleteDocument($id);
                    break;
                case "asset":
                    $this->deleteAsset($id);
                    break;
                default:
                    $output->writeln("Incorrect tree type, allowed types: object, document, asset.");
                    return;
            }

            $output->writeln("Element ".$id." has been deleted.");
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }

    }

    /**
     * @param int $id
     * @return void
     * @throws \Exception
     */
    protected function deleteObject(int $id)
    {
        $element = AbstractObject::getById($id);
        if(!$element) {
            throw new \Exception("Object with ID ".$id." does not exist.");
        } else {
            $element->delete();
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws \Exception
     */
    protected function deleteDocument(int $id)
    {
        $element = Document::getById($id);
        if(!$element) {
            throw new \Exception("Document with ID ".$id." does not exist.");
        } else {
            $element->delete();
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws \Exception
     */
    protected function deleteAsset(int $id)
    {
        $element = Asset::getById($id);
        if(!$element) {
            throw new \Exception("Asset with ID ".$id." does not exist.");
        } else {
            $element->delete();
        }
    }
}
