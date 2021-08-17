<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Provider\DataTables;

use WBW\Bundle\CoreBundle\EventListener\KernelEventListenerTrait;
use WBW\Bundle\CoreBundle\Twig\Extension\AbstractTwigExtension;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\DocumentIconProviderTrait;
use WBW\Bundle\EDMBundle\Translation\TranslatorInterface;
use WBW\Bundle\JQuery\DataTablesBundle\Provider\AbstractDataTablesProvider as BaseDataTablesProvider;
use WBW\Library\Types\Helper\StringHelper;

/**
 * Abstract DataTables provider.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider\DataTables
 */
abstract class AbstractDataTablesProvider extends BaseDataTablesProvider {

    use DocumentIconProviderTrait {
        setDocumentIconProvider as public;
    }

    use KernelEventListenerTrait {
        setKernelEventListener as public;
    }

    /**
     * Render an action button "download".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered action button "download".
     */
    protected function renderActionButtonDownload(DocumentInterface $document): string {

        $title  = $this->translate("label.download");
        $button = $this->getButtonTwigExtension()->bootstrapButtonInfoFunction(["icon" => "fa:download", "title" => $title, "size" => "xs"]);
        $url    = $this->getRouter()->generate("wbw_edm_document_download", ["id" => $document->getId()]);

        return $this->getButtonTwigExtension()->bootstrapButtonLinkFilter($button, $url);
    }

    /**
     * Render an action button "index".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered action button "index".
     */
    protected function renderActionButtonIndex(DocumentInterface $document): string {

        $title  = $this->translate("label.index");
        $button = $this->getButtonTwigExtension()->bootstrapButtonPrimaryFunction(["icon" => "fa:folder-open", "title" => $title, "size" => "xs"]);
        $url    = $this->getRouter()->generate("wbw_edm_document_index", ["id" => $document->getId()]);

        return $this->getButtonTwigExtension()->bootstrapButtonLinkFilter($button, $url);
    }

    /**
     * Render an action button "move".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered action button "move".
     */
    protected function renderActionButtonMove(DocumentInterface $document): string {

        $title  = $this->translate("label.move");
        $button = $this->getButtonTwigExtension()->bootstrapButtonDefaultFunction(["icon" => "fa:arrows-alt", "title" => $title, "size" => "xs"]);
        $url    = $this->getRouter()->generate("wbw_edm_document_move", ["id" => $document->getId()]);

        return $this->getButtonTwigExtension()->bootstrapButtonLinkFilter($button, $url);
    }

    /**
     * Render an action button "upload".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered action button "upload".
     */
    protected function renderActionButtonUpload(DocumentInterface $document): string {

        $title  = $this->translate("label.upload");
        $button = $this->getButtonTwigExtension()->bootstrapButtonSuccessFunction(["icon" => "fa:upload", "title" => $title, "size" => "xs"]);
        $url    = $this->getRouter()->generate("wbw_edm_dropzone_upload", ["id" => $document->getId()]);

        return $this->getButtonTwigExtension()->bootstrapButtonLinkFilter($button, $url);
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

        $format = '<img src="%s" height="32px" />';
        $output = sprintf($format, $this->getDocumentIconProvider()->getIconAsset($document));

        return AbstractTwigExtension::coreHTMLElement("span", $output, ["class" => "pull-left"]);
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
            $output[] = AbstractTwigExtension::coreHTMLElement("span", $content, ["class" => "font-italic"]);
        }

        $icon = $this->renderColumnIcon($document);
        $name = implode("<br/>", $output);

        return "{$icon}{$name}";
    }

    /**
     * Render a column "size".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "size".
     */
    protected function renderColumnSize(DocumentInterface $document): string {

        $output = StringHelper::fileSize($document->getSize());

        return AbstractTwigExtension::coreHTMLElement("span", $output, ["class" => "pull-right"]);
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
     * {@inheritDoc}
     */
    protected function translate(string $id, array $parameters = [], string $domain = null, string $locale = null): string {
        if (null === $domain) {
            $domain = TranslatorInterface::DOMAIN;
        }
        return parent::translate($id, $parameters, $domain, $locale);
    }
}
