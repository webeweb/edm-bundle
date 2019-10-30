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

use WBW\Bundle\CoreBundle\Renderer\FileSizeRenderer;
use WBW\Bundle\CoreBundle\Twig\Extension\AbstractTwigExtension;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\DocumentIconProviderTrait;
use WBW\Bundle\EDMBundle\Translation\TranslationInterface;
use WBW\Bundle\JQuery\DataTablesBundle\Provider\AbstractDataTablesProvider as BaseDataTablesProvider;

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

    /**
     * Render a column "icon".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "icon".
     */
    protected function renderColumnIcon(DocumentInterface $document) {

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
    protected function renderColumnName(DocumentInterface $document) {

        $output = [
            DocumentHelper::getFilename($document),
        ];

        if (true === $document->isDirectory()) {
            $content  = $this->translate("label.items_count", ["{{ count }}" => count($document->getChildren())]);
            $output[] = AbstractTwigExtension::coreHTMLElement("span", $content, ["class" => "font-italic"]);
        }

        $icon = $this->renderColumnIcon($document);
        $name = implode("<br/>", $output);

        return "${icon}${name}";
    }

    /**
     * Render a column "size".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "size".
     */
    protected function renderColumnSize(DocumentInterface $document) {

        $output = FileSizeRenderer::renderSize($document->getSize());

        return AbstractTwigExtension::coreHTMLElement("span", $output, ["class" => "pull-right"]);
    }

    /**
     * Render a column "type".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "type".
     */
    protected function renderColumnType(DocumentInterface $document) {
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
    protected function renderColumnUpdatedAt(DocumentInterface $document) {
        if (null !== $document->getUpdatedAt()) {
            return $this->renderDateTime($document->getUpdatedAt());
        }
        return $this->renderDateTime($document->getCreatedAt());
    }

    /**
     * Translate.
     *
     * @param string $id The id.
     * @param array $parameters The parameters.
     * @return string Returns the translation in case of success, $id otherwise.
     */
    protected function translate($id, array $parameters = []) {
        return $this->getTranslator()->trans($id, $parameters, TranslationInterface::TRANSLATION_DOMAIN);
    }
}
