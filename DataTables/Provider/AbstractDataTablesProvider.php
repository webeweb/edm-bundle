<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\DataTables\Provider;

use WBW\Bundle\CoreBundle\EventListener\KernelEventListenerTrait;
use WBW\Bundle\CoreBundle\Twig\Extension\AbstractTwigExtension;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\DocumentIconProviderTrait;
use WBW\Bundle\EDMBundle\WBWEDMBundle;
use WBW\Bundle\JQuery\DataTablesBundle\Provider\AbstractDataTablesProvider as BaseDataTablesProvider;
use WBW\Library\Symfony\Renderer\Assets\ImageRendererTrait;
use WBW\Library\Types\Helper\StringHelper;

/**
 * Abstract DataTables provider.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Provider\DataTables
 */
abstract class AbstractDataTablesProvider extends BaseDataTablesProvider {

    use DocumentIconProviderTrait {
        setDocumentIconProvider as public;
    }
    use KernelEventListenerTrait {
        setKernelEventListener as public;
    }
    use ImageRendererTrait;

    /**
     * Render an action button.
     *
     * @param DocumentInterface $document The document.
     * @param string $route The route.
     * @param string $icon The icon.
     * @param string $label The label.
     * @param string $type The type.
     * @return string Returns the rendered action button.
     */
    private function renderActionButton(DocumentInterface $document, string $route, string $icon, string $label, string $type): string {

        $method = sprintf("bootstrapButton%sFunction", $type);

        $title  = $this->translate($label);
        $button = $this->getButtonTwigExtension()->$method(["icon" => $icon, "title" => $title, "size" => "xs"]);
        $href   = $this->getRouter()->generate($route, ["id" => $document->getId()]);

        return $this->getButtonTwigExtension()->bootstrapButtonLinkFilter($button, $href);
    }

    /**
     * Render an action button "download".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered action button "download".
     */
    protected function renderActionButtonDownload(DocumentInterface $document): string {
        return $this->renderActionButton($document, "wbw_edm_document_download", "fa:download", "label.download", "Info");
    }

    /**
     * Render an action button "index".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered action button "index".
     */
    protected function renderActionButtonIndex(DocumentInterface $document): string {
        return $this->renderActionButton($document, "wbw_edm_document_index", "fa:folder-open", "label.index", "Primary");
    }

    /**
     * Render an action button "move".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered action button "move".
     */
    protected function renderActionButtonMove(DocumentInterface $document): string {
        return $this->renderActionButton($document, "wbw_edm_document_move", "fa:arrows-alt", "label.move", "Default");
    }

    /**
     * Render an action button "upload".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered action button "upload".
     */
    protected function renderActionButtonUpload(DocumentInterface $document): string {
        return $this->renderActionButton($document, "wbw_edm_dropzone_upload", "fa:upload", "label.upload", "Success");
    }

    /**
     * Render a column "actions".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "actions".
     */
    protected function renderColumnActions(DocumentInterface $document): string {

        $anchors = [
            $this->renderActionButtonEdit($document, "wbw_edm_document_edit"),
            $this->renderActionButtonDelete($document, "wbw_edm_document_delete"),
            $this->renderActionButtonDownload($document),
            $this->renderActionButtonMove($document),
        ];

        if (true === $document->isDirectory()) {
            $anchors[] = $this->renderActionButtonIndex($document);
            $anchors[] = $this->renderActionButtonUpload($document);
        }

        return implode(" ", $anchors);
    }

    /**
     * Render a column "icon".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "icon".
     */
    protected function renderColumnIcon(DocumentInterface $document): string {

        $output = $this->renderImage($this->getDocumentIconProvider()->getIconAsset($document), null, null, "32px");

        return AbstractTwigExtension::coreHtmlElement("span", $output, ["class" => "pull-left"]);
    }

    /**
     * Render a column "name".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "name".
     */
    protected function renderColumnName(DocumentInterface $document): string {

        $output = [
            DocumentHelper::getFilename($document),
        ];

        if (true === $document->isDirectory()) {
            $content  = $this->translate("label.items_count", ["{{ count }}" => count($document->getChildren())]);
            $output[] = AbstractTwigExtension::coreHtmlElement("span", $content, ["class" => "font-italic"]);
        }

        $icon = $this->renderColumnIcon($document);
        $name = implode("<br/>", $output);

        return "$icon$name";
    }

    /**
     * Render a column "size".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "size".
     */
    protected function renderColumnSize(DocumentInterface $document): string {

        $output = StringHelper::fileSize($document->getSize());

        return AbstractTwigExtension::coreHtmlElement("span", $output, ["class" => "pull-right"]);
    }

    /**
     * Render a column "type".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "type".
     */
    protected function renderColumnType(DocumentInterface $document): string {

        if (true === $document->isDirectory()) {
            return $this->translate("label.directory");
        }

        return $document->getMimeType();
    }

    /**
     * Render a column "updated at".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "updated at".
     */
    protected function renderColumnUpdatedAt(DocumentInterface $document): string {

        if (null !== $document->getUpdatedAt()) {
            return $this->renderDateTime($document->getUpdatedAt());
        }

        return $this->renderDateTime($document->getCreatedAt());
    }

    /**
     * {@inheritdoc}
     */
    protected function translate(?string $id, array $parameters = [], string $domain = null, string $locale = null): string {

        if (null === $domain) {
            $domain = WBWEDMBundle::getTranslationDomain();
        }

        return parent::translate($id, $parameters, $domain, $locale);
    }
}
