<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        12/12/2017
 * @author      Jakub Płaskonka <jplaskonka@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */


namespace PimcoreDevkitBundle\Service;

use Pimcore\Model\DataObject\ClassDefinition;

/**
 * Class ClassDefinitionService
 *
 * @package PimcoreDevkitBundle\Service
 */
class ClassDefinitionService
{
    /**
     * @param array $definition
     * @param callable $callback
     * Will run $callback on every Data
     */
    public function traverseDefinition(array &$definition, Callable $callback) {
        foreach($definition as $def) {
            if (method_exists($def, "hasChildren")) {
                if ($def->hasChildren()) {
                    $this->traverseDefinition($def->getChildren(), $callback);
                }
            }

            if ($def instanceof ClassDefinition\Data) {
                $callback($def);
            }
        }
    }

    /**
     * @param ClassDefinition\Layout $definition
     * @param callable $callback
     * Will run $callback on every Data
     */
    public function traverseLayout(ClassDefinition\Layout &$layout, Callable $callback) {
        $callback($layout);

        foreach($layout->childs as $child) {
            if ($child instanceof ClassDefinition\Layout) {
                $this->traverseLayout($child, $callback);
            }
        }
    }

    /**
     * @param array $definition
     * @param string $name
     * @param $newValue
     * @return array Definition
     */
    public function replaceDefinition(array $definition, string $name, $newValue) : array
    {
        foreach($definition as $key => &$def) {
            if (method_exists($def, "getName")) {
                if ($def->getName() == $name) {
                   $definition[$key] = $newValue;
                   return $definition;
                }
            }
        }

        foreach($definition as $key => $def) {
            if (method_exists($def, "hasChildren")) {
                if ($def->hasChildren()) {
                    $def->setChildren($this->replaceDefinition($def->getChildren(), $name, $newValue));
                }
            }
        }

        return $definition;
    }
}