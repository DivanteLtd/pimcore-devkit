<?php
/**
 * @category    Pimcore 5 DevKit
 * @date        08/12/2017
 *
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

namespace PimcoreDevkitBundle\Model\CustomView;

use Pimcore\Config;
use Pimcore\Model\Dao\DaoInterface;
use Pimcore\Model\Dao\DaoTrait;
use PimcoreDevkitBundle\Db\CustomViews;

/**
 * Class Dao
 *
 * @package PimcoreDevkitBundle\Model\CustomView
 */
class Dao implements DaoInterface
{
    use DaoTrait;

    /**
     * @var CustomViews
     */
    protected $db;

    public function configure()
    {
        $this->setFile('customviews');
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     */
    public function getById($id = null)
    {
        if (null != $id) {
            $this->model->setId($id);
        }

        $data = $this->db->getById($this->model->getId());

        if (isset($data['id'])) {
            $this->assignVariablesToModel($data);
        } else {
            throw new \Exception('Custom view with id: '.$this->model->getId().' does not exist');
        }
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        try {
            $dataRaw = get_object_vars($this->model);
            $data = [];

            foreach ($dataRaw as $key => $value) {
                $data[$key] = $value;
            }

            $this->db->insertOrUpdate($data, $this->model->getId());
        } catch (\Exception $e) {
            throw $e;
        }

        if (!$this->model->getId()) {
            $this->model->setId($this->db->getLastInsertId());
        }
    }

    public function delete()
    {
        $this->db->delete($this->model->getId());
    }

    /**
     * @param $name string
     */
    protected function setFile($name)
    {
        $file = Config::locateConfigFile($name.'.php');
        $this->db = CustomViews::get($file);
    }
}
