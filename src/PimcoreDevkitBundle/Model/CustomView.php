<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        08/12/2017
 *
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

namespace PimcoreDevkitBundle\Model;

use Pimcore\Logger;
use Pimcore\Model\AbstractModel;

/**
 * Class CustomView
 *
 * @package PimcoreDevkitBundle\Model
 */
class CustomView extends AbstractModel
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $treetype;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $condition;
    /**
     * @var string
     */
    public $icon;
    /**
     * @var string
     */
    public $rootfolder;
    /**
     * @var bool
     */
    public $showroot;
    /**
     * @var string
     */
    public $classes;
    /**
     * @var string
     */
    public $position;
    /**
     * @var string
     */
    public $sort;
    /**
     * @var bool
     */
    public $expanded;
    /**
     * @var string
     */
    public $having;
    /**
     * @var array
     */
    public $joins;
    /**
     * @var string
     */
    public $where;
    /**
     * @var array
     */
    public $treeContextMenu;

    /**
     * @param $id
     *
     * @return CustomView|null
     */
    public static function getById($id)
    {
        $cacheKey = 'customview_'.$id;

        try {
            $customView = \Pimcore\Cache\Runtime::get($cacheKey);
            if (!$customView) {
                throw new \Exception('Custom view in registry is null');
            }
        } catch (\Exception $e) {
            try {
                $customView = new self();
                \Pimcore\Cache\Runtime::set($cacheKey, $customView);
                $customView->setId(intval($id));
                $customView->getDao()->getById();
            } catch (\Exception $e) {
                Logger::error($e);

                return null;
            }
        }

        return $customView;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTreetype()
    {
        return $this->treetype;
    }

    /**
     * @param string $treetype
     */
    public function setTreetype($treetype)
    {
        $this->treetype = $treetype;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getRootfolder()
    {
        return $this->rootfolder;
    }

    /**
     * @param string $rootfolder
     */
    public function setRootfolder($rootfolder)
    {
        $this->rootfolder = $rootfolder;
    }

    /**
     * @return bool
     */
    public function isShowroot()
    {
        return $this->showroot;
    }

    /**
     * @param bool $showroot
     */
    public function setShowroot($showroot)
    {
        $this->showroot = $showroot;
    }

    /**
     * @return string
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param string $classes
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return bool
     */
    public function isExpanded()
    {
        return $this->expanded;
    }

    /**
     * @param bool $expanded
     */
    public function setExpanded($expanded)
    {
        $this->expanded = $expanded;
    }

    /**
     * @return string
     */
    public function getHaving()
    {
        return $this->having;
    }

    /**
     * @param string $having
     */
    public function setHaving($having)
    {
        $this->having = $having;
    }

    /**
     * @return array
     */
    public function getJoins()
    {
        return $this->joins;
    }

    /**
     * @param array $joins
     */
    public function setJoins($joins)
    {
        $this->joins = $joins;
    }

    /**
     * @return string
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @param string $where
     */
    public function setWhere($where)
    {
        $this->where = $where;
    }

    /**
     * @return array
     */
    public function getTreeContextMenu()
    {
        return $this->treeContextMenu;
    }

    /**
     * @param array $treeContextMenu
     */
    public function setTreeContextMenu($treeContextMenu)
    {
        $this->treeContextMenu = $treeContextMenu;
    }
}
