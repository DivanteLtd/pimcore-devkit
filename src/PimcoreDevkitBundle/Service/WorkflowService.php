<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        28/11/2017
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\Workflow;

/**
 * Class WorkflowService
 * @package PimcoreDevkitBundle\Service
 */
class WorkflowService
{
    /**
     * @param int $classId
     * @return int
     */
    public function countByClassId(int $classId) : int
    {
        $listing = new Workflow\Listing();
        $listing->setFilter($this->getClassIdFilter($classId));
        return $listing->getTotalCount();
    }

    /**
     * @param int $classId
     * @return array
     */
    public function getByClassId(int $classId) : array
    {
        $listing = new Workflow\Listing();
        $listing->setFilter($this->getClassIdFilter($classId));
        return $listing->load();
    }

    /**
     * @param int $classId
     * @return callable
     */
    protected function getClassIdFilter(int $classId) : callable
    {
        return function (array $row) use ($classId) : bool {
            return in_array($classId, $row['workflowSubject']['classes']);
        };
    }

    /**
     * @param string $filePath
     * @param array $customValues
     */
    public function createFromFile(string $filePath, array $customValues = [])
    {
        $values = $this->getValues($filePath);
        $values = array_merge_recursive($values, $customValues);

        $workflow = new Workflow();
        $workflow->setValues($values);
        $workflow->save();
    }

    /**
     * @param string $filePath
     * @return array
     */
    protected function getValues(string $filePath) : array
    {
        $values = include $filePath;
        return $values;
    }
}
