<?php

namespace PimcoreDevkitBundle;

use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class PimcoreDevkitBundle extends AbstractPimcoreBundle
{
  use PackageVersionTrait;

  /**
   * @return string
   */
  public function getComposerPackageName(): string
  {
      return 'divanteltd/pimcoredevkit';
  }

  /**
   * @return string
   */
  public function getNiceName()
  {
      return 'Pimcore 5 DevKit';
  }

  /**
   * @return string
   */
  public function getDescription()
  {
      return 'This is a package that contains a set of tools commonly used in many projects';
  }  
}
