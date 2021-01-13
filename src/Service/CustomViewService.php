<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        12/12/2017
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use PimcoreDevkitBundle\Model\CustomView;

/**
 * Class CustomViewService
 * @package PimcoreDevkitBundle\Service
 */
class CustomViewService
{
    /**
     * @param string $filePath
     * @param array $customData
     * @return CustomView
     */
    public function createFromFile(string $filePath, array $customData = []) : CustomView
    {
        $data = include $filePath;
        $data = array_merge_recursive($data, $customData);

        $customView = new CustomView();
        $customView->setValues($data);
        $customView->save();
        
        return $customView;
    }
}
