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
     * DocumentConfiguration constructor.
     * @param string|null $documentClassFqcn
     * @param string|null $controller
     * @param string|null $action
     * @param string|null $template
     * @param string|null $module
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
     * @return void
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
     * @return void
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     * @return void
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param string $module
     * @return void
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
     * @return void
     */
    public function setDocumentFqcn(string $classFqcn)
    {
        $this->class = $classFqcn;
    }
}
