<?php
/**
 * @date        05/02/2019
 * @author      Michał Bolka <michal.bolka@gmail.com>
 * @copyright   Copyright (c) 2019 Divante (http://divante.pl)
 */

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\DataObject\ClassDefinition\CustomLayout;
use Pimcore\Model\DataObject\ClassDefinition\Service;
use Symfony\Component\Serializer\Serializer;

/**
 * Class CustomLayoutsService
 * @package PimcoreDevkitBundle\Service
 */
class CustomLayoutsService
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * CustomLayoutsService constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param string $name
     * @param int    $classId
     * @param string $filePath
     * @throws \Exception
     */
    public function importLayout(string $name, int $classId, string $filePath)
    {
        $json         = file_get_contents($filePath);
        $importData   = $this->decodeJson($json);
        $customLayout = new CustomLayout();
        $id = $this->suggestId($classId);
        $customLayout->setClassId($classId);
        $customLayout->setName($name);
        $customLayout->setId($id);
        $layout       = Service::generateLayoutTreeFromArray($importData['layoutDefinitions'], true);
        $customLayout->setLayoutDefinitions($layout);
        $customLayout->setDescription($importData['description']);
        $customLayout->save();
    }

    /**
     * @param string $json
     * @return mixed
     */
    public function decodeJson(string $json)
    {
        $context['json_decode_associative'] = true;
        return $this->serializer->decode($json, 'json', $context);
    }

    /**
     * @param $classId
     * @return int|null
     */
    public function suggestId($classId)
    {
        return CustomLayout::getIdentifier($classId);
    }
}

