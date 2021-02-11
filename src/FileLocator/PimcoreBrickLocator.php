<?php
/**
 * @category    Pimcore DevKit
 * @date        17/06/2019
 * @author      Michał Bolka <mbolka@divante.pl>
 * @copyright   Copyright (c) 2019 Divante Ltd. (https://divante.co)
 */

namespace PimcoreDevkitBundle\FileLocator;

/**
 * Class PimcoreBrickLocator
 * @package ClassInstallerBundle\FileLocator
 */
class PimcoreBrickLocator extends PimcoreBundlesFilesLocator
{
    const SUBCATALOG = 'Resources/objectbricks';
    const FILE_REGEX = '/objectbrick\_(\w*)\_export\.json/';
}
