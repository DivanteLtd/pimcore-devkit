<?php
/**
 * @category    Divante
 * @date        17/05/2019 11:12
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2019 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\Command;

use PimcoreDevkitBundle\Service\DataObjectService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RemoveAllObjectsCommand
 * @package PimcoreDevkitBundle\Command
 */
class RemoveAllObjectsCommand extends ContainerAwareCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('devkit:object:remove_all')
            ->addOption(
                'classes',
                'c',
                InputArgument::OPTIONAL,
                'DataObjects classes list (without namespace) separated by comma',
                []
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $classesList = $input->getOption('classes');

        $classesNames = explode(',', $classesList);
        $service = new DataObjectService();
        foreach ($classesNames as $className) {
            $fullClassName = "\\Pimcore\\Model\\DataObject\\" . $className;
            $output->write("Removing all objects of class: $fullClassName ... ");
            $cnt = 0;
            foreach ($service->removeAllObjects($fullClassName) as $message) {
                ++$cnt;
                $output->writeln($message);
            }

            $output->writeln("TOTAL REMOVED:  $cnt");
        }
    }
}
