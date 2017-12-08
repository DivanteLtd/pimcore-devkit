<?php
/**
 * @category    Wurth
 * @date        08/12/2017
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

namespace PimcoreDevkitBundle\Db;

use Pimcore\Db\PhpArrayFileTable;
use Pimcore\File;

/**
 * Class CustomViews
 * @package PimcoreDevkitBundle\Db
 */
class CustomViews extends PhpArrayFileTable
{
    /**
     * @param $filePath string
     * @return CustomViews
     */
    public static function get($filePath)
    {
        if (!isset(self::$tables[$filePath])) {
            self::$tables[$filePath] = new self($filePath);
        }

        return self::$tables[$filePath];
    }

    /**
     *
     */
    protected function load()
    {
        if (file_exists($this->filePath)) {
            $data = include($this->filePath);
            if (!is_array($data)) {
                $data = [];
            }
            if (!array_key_exists('views', $data) || !is_array($data['views'])) {
                $data['views'] = [];
            }
            $this->data = $data['views'];
        }
    }

    /**
     *
     */
    protected function save()
    {
        $contents = to_php_data_file_format(['views' => $this->data]);
        File::putPhpFile($this->filePath, $contents);
    }
}
