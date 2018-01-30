<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Twig\Extension;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Extension;
use Twig_SimpleFunction;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Library\Core\Utility\FileUtility;
use WBW\Library\Core\Utility\StringUtility;

/**
 * EDM Twig extension.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Twig\Extension
 * @final
 */
final class EDMTwigExtension extends Twig_Extension {

    /**
     * Service name.
     */
    const SERVICE_NAME = "webeweb.bundle.edmbundle.twig.extension.edm";

    /**
     * Router.
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * Storage manager.
     *
     * @var StorageManager
     */
    private $storage;

    /**
     * Translator.
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator The translator service.
     * @param StorageManager $storage The storage manager service.
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator, StorageManager $storage) {
        $this->router     = $router;
        $this->storage    = $storage;
        $this->translator = $translator;
    }

    /**
     * Displays a link.
     *
     * @param Document $directory The document.
     * @return string Returns the link.
     */
    public function edmLinkFunction(Document $directory) {

        // Check the document type.
        if ($directory->isDocument()) {
            return $directory->getFilename();
        }

        // Initialize the template.
        $template = '<a %attributes%>%content%</a>';

        // Initialize the attributes.
        $attr = [];

        $attr["class"]          = ["btn", "btn-link"];
        $attr["href"]           = $this->router->generate("edm_directory_open", ["id" => $directory->getId()]);
        $attr["title"]          = implode(" ", [$this->translator->trans("label.open", [], "EDMBundle"), $directory->getFilename()]);
        $attr["data-toggle"]    = "tooltip";
        $attr["data-placement"] = "right";

        // Return.
        return str_replace(["%attributes%", "%content%"], [StringUtility::parseArray($attr), $directory->getFilename()], $template);
    }

    /**
     * Displays a pathname.
     *
     * @param Document $document The document.
     * @return string Returns the pathname.
     */
    public function edmPathFunction(Document $document = null) {
        return "/" . (null !== $document ? $document->getPathname() : "");
    }

    /**
     * Displays a size.
     *
     * @param Document $document The document.
     * @return string Returns the size.
     */
    public function edmSizeFunction(Document $document) {

        // Initialiaze the template.
        $template = "<span %attributes%>%content%</span>";

        // Format the size.
        $size = FileUtility::formatSize($document->getSize());

        // Initialize the attributes.
        $attr = [];

        $attr["title"]          = $size;
        $attr["data-toggle"]    = "tooltip";
        $attr["data-placement"] = "bottom";

        // Initialize the content.
        $content = $size;
        if ($document->isDirectory()) {
            $content = implode(" ", [count($document->getChildrens()), strtolower($this->translator->trans("label.items", [], "EDMBundle"))]);
        }

        // Return.
        return str_replace(["%attributes%", "%content%"], [StringUtility::parseArray($attr), $content], $template);
    }

    /**
     * Get the Twig functions.
     *
     * @return array Returns the Twig functions.
     */
    public function getFunctions() {
        return [
            new Twig_SimpleFunction('edmLink', [$this, 'edmLinkFunction'], ["is_safe" => ["html"]]),
            new Twig_SimpleFunction('edmPath', [$this, 'edmPathFunction']),
            new Twig_SimpleFunction('edmSize', [$this, 'edmSizeFunction'], ["is_safe" => ["html"]]),
        ];
    }

}
