<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Twig\Extension;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Extension;
use Twig_SimpleFunction;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Library\Core\Argument\StringHelper;
use WBW\Library\Core\FileSystem\FileHelper;

/**
 * EDM Twig extension.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Twig\Extension
 */
class EDMTwigExtension extends Twig_Extension {

    /**
     * Service name.
     */
    const SERVICE_NAME = "webeweb.edm.twig.extension.edm";

    /**
     * Router.
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * Translator.
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param RouterInterface $router The router service.
     * @param TranslatorInterface $translator The translator service.
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator) {
        $this->router     = $router;
        $this->translator = $translator;
    }

    /**
     * Displays a link.
     *
     * @param DocumentInterface $directory The document.
     * @return string Returns the link.
     */
    public function edmLinkFunction(DocumentInterface $directory) {

// Get the filename.
        $filename = DocumentHelper::getFilename($directory);

// Check the document type.
        if ($directory->isDocument()) {
            return $filename;
        }

// Initialize the template.
        $template = '<a %attributes%>%innerHTML%</a>';

// Initialize the attributes.
        $attributes = [];

        $attributes["class"]          = ["btn", "btn-link"];
        $attributes["href"]           = $this->router->generate("edm_directory_open", ["id" => $directory->getId()]);
        $attributes["title"]          = implode(" ", [$this->translator->trans("label.open", [], "EDMBundle"), $filename]);
        $attributes["data-toggle"]    = "tooltip";
        $attributes["data-placement"] = "right";

// Return.
        return str_replace(["%attributes%", "%innerHTML%"], [StringHelper::parseArray($attributes), $filename], $template);
    }

    /**
     * Displays a pathname.
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the pathname.
     */
    public function edmPathFunction(DocumentInterface $document = null) {
        return "/" . (null !== $document ? DocumentHelper::getPathname($document) : "");
    }

    /**
     * Displays a size.
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the size.
     */
    public function edmSizeFunction(DocumentInterface $document) {

// Initialiaze the template.
        $template = "<span %attributes%>%innerHTML%</span>";

// Format the size.
        $size = FileHelper::formatSize($document->getSize());

// Initialize the attributes.
        $attributes = [];

        $attributes["title"]          = $size;
        $attributes["data-toggle"]    = "tooltip";
        $attributes["data-placement"] = "bottom";

// Initialize the content.
        $innerHTML = $size;
        if ($document->isDirectory()) {
            $innerHTML = implode(" ", [count($document->getChildrens()), strtolower($this->translator->trans("label.items", [], "EDMBundle"))]);
        }

// Return.
        return str_replace(["%attributes%", "%innerHTML%"], [StringHelper::parseArray($attributes), $innerHTML], $template);
    }

    /**
     * Get the Twig functions.
     *
     * @return Twig_SimpleFunction[] Returns the Twig functions.
     */
    public function getFunctions() {
        return [
            new Twig_SimpleFunction('edmLink', [$this, 'edmLinkFunction'], ["is_safe" => ["html"]]),
            new Twig_SimpleFunction('edmPath', [$this, 'edmPathFunction']),
            new Twig_SimpleFunction('edmSize', [$this, 'edmSizeFunction'], ["is_safe" => ["html"]]),
        ];
    }

}
