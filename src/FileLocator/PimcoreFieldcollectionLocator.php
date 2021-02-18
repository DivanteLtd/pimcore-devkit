<?php
declare(strict_types=1);

namespace PimcoreDevkitBundle\FileLocator;

class PimcoreFieldcollectionLocator extends PimcoreBundlesFilesLocator
{
    protected const SUBCATALOG = 'Resources/fieldcollections';
    protected const FILE_REGEX = '/fieldcollection\_(\w*)\_export\.json/';
}
