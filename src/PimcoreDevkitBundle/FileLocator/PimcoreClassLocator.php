<?php
/**
 * @category    Pimcore DevKit
 * @date        17/06/2019
 * @author      Michał Bolka <mbolka@divante.pl>
 * @copyright   Copyright (c) 2019 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\FileLocator;

/**
 * Class PimcoreClassLocator
 * @package PimcoreDevkitBundle\FileLocator
 */
class PimcoreClassLocator extends PimcoreBundlesFilesLocator
{
    const SUBCATALOG = 'Resources/classes';
    const FILE_REGEX = '/class\_(\w*)\_export\.json/';
}
