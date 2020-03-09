<?php

namespace PimcoreDevkitBundle\Model;

/**
 * Class AssetSeoData
 * @package PimcoreDevkitBundle\Model
 */
class AssetSeoData
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $alt = '';

    /**
     * @param string $title
     * @return AssetSeoData
     */
    public function setTitle(string $title): AssetSeoData
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $alt
     * @return AssetSeoData
     */
    public function setAlt(string $alt): AssetSeoData
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }
}
