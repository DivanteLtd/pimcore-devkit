<?php

namespace PimcoreDevkitBundle\Document\Areabrick;

use Pimcore\Model\Document\Tag;
use PimcoreDevkitBundle\Service\Wysiwyg\WysiwygService;
use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Asset;
use Pimcore\Model\Document\Tag\Area\Info;

/**
 * Class WysiwygWithMetadata
 * @package PimcoreDevkitBundle\Document\Areabrick
 */
class WysiwygWithMetadata extends AbstractTemplateAreabrick
{
    protected const TAG_TYPE = 'wysiwyg';
    protected const TAG_NAME = 'content';

    /**
     * @var WysiwygService
     */
    private $wysiwygService;

    /**
     * WysiwygWithMetadata constructor.
     * @param WysiwygService $wysiwygService
     */
    public function __construct(WysiwygService $wysiwygService)
    {
        $this->wysiwygService = $wysiwygService;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WYSIWYG with metadata';
    }

    /**
     * @return string|null
     */
    public function getIcon()
    {
        return '/bundles/pimcoreadmin/img/flat-color-icons/wysiwyg.svg';
    }

    /**
     * @param Info $info
     * @return \Symfony\Component\HttpFoundation\Response|void|null
     * @throws \Exception
     */
    public function action(Info $info)
    {
        $lang = $info->getDocument()->getProperty("language");

        $tag = $this->getDocumentTag($info->getDocument(), self::TAG_TYPE, self::TAG_NAME);
        if (!$tag instanceof Tag) {
            return;
        }
        $html = $tag->getData();
        if (!$html) {
            return;
        }

        $html = $this->wysiwygService->addMetaToImages($html, $lang);

        $tag->setDataFromResource($html);
    }

    /**
     * @param Info $info
     * @return string
     */
    public function getHtmlTagOpen(Info $info)
    {
        return '';
    }

    /**
     * @param Info $info
     * @return string
     */
    public function getHtmlTagClose(Info $info)
    {
        return '';
    }

    /**
     * @return string
     */
    public function getTemplateLocation()
    {
        return static::TEMPLATE_LOCATION_BUNDLE;
    }

    /**
     * @return string
     */
    public function getTemplateSuffix()
    {
        return static::TEMPLATE_SUFFIX_TWIG;
    }
}
