<?php
declare(strict_types=1);

namespace PimcoreDevkitBundle\FileLocator;

class PimcoreFieldcollectionLocator extends PimcoreBundlesFilesLocator
{
    const SUBCATALOG = 'Resources/fieldcollections';
    const FILE_REGEX = '/fieldcollection\_(\w*)\_export\.json/';
}
