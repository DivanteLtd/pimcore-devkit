<?php

/**
 * @date        27/11/2018
 * @author      Kamil Wręczycki <kwreczycki@divante.pl>
 * @copyright   Copyright (c) 2017 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace PimcoreDevkitBundle\Service\Configuration;

/**
 * Class DocumentConfiguration
 *
 * @package PimcoreDevkitBundle\Service
 */
class DocumentConfiguration
{
    /** @var string */
    protected $controller;

    /** @var string */
    protected $action;

    /** @var string */
    protected $template;

    /** @var string */
    protected $module;

    /** @var string */
    protected $class;

    /**
     * @param string $documentClassFqcn
     * @param string $controller
     * @param string $action
     * @param string $template
     * @param string $module
     */
    public function __construct(
        string $documentClassFqcn = null,
        string $controller = null,
        string $action = null,
        string $template = null,
        string $module = null
    ) {
        $this->class = $documentClassFqcn;
        $this->controller = $controller;
        $this->action = $action;
        $this->template = $template;
        $this->module = $module;
    }

    /**
     * @param string $controller
     */
    public function setController(string $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getTemplate():? string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getModule():? string
    {
        return $this->module;
    }

    /**
     * @param string $module
     */
    public function setModule(string $module)
    {
        $this->module = $module;
    }

    /**
     * @return string
     */
    public function getDocumentFqcn(): string
    {
        return $this->class;
    }

    /**
     * @param string $classFqcn
     */
    public function setDocumentFqcn(string $classFqcn)
    {
        $this->class = $classFqcn;
    }
}
