<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        08/12/2017
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

namespace PimcoreDevkitBundle\Model;

use Pimcore\Model\AbstractModel;
use Pimcore\Logger;

/**
 * Class CustomView
 * @package PimcoreDevkitBundle\Model
 * @SuppressWarnings(PHPMD)
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
     * @param int $id
     * @return CustomView|null
     */
    public static function getById($id)
    {
        $cacheKey = 'customview_' . $id;

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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
