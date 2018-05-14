<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        26/01/2018
 * @author      Wojciech Peisert <wpeisert@divante.pl>
 * @copyright   Copyright (c) 2018 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Config;
use Pimcore\File;
use Pimcore\Model\Document\DocType;

/**
 * Class DocTypeService
 *
 * @package PimcoreDevkitBundle\Service
 */
class DocTypeService
{
    /**
     * Returns DocType by name
     * @param string $name
     * @return null|DocType
     */
    public function getDocTypeByName($name) : ?DocType
    {
        $list = new DocType\Listing();
        $list->setFilter(function ($row) use ($name) {
            if ($row['name'] == $name) {
                return true;
            }
            return false;
        });
        $list->load();

        $docTypes = $list->getDocTypes();
        if (isset($docTypes[0])) {
            return $docTypes[0];
        }

        return null;
    }
}
