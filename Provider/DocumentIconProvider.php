<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Provider;

use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Library\Traits\Strings\StringDirectoryTrait;

/**
 * Document icon provider.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Provider
 */
class DocumentIconProvider {

    use StringDirectoryTrait;

    /**
     * Default icon.
     *
     * @var string
     */
    const DEFAULT_ICON = "unknown.svg";

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "wbw.edm.provider.document_icon";

    /**
     * Constructor.
     */
    public function __construct() {
        $this->setDirectory(realpath(__DIR__ . "/../Resources/public/img"));
    }

    /**
     * Get an icon.
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the icon.
     */
    public function getIcon(DocumentInterface $document): string {

        if ($document->isDirectory()) {
            return "folder.svg";
        }

        $mimeType = str_replace("/", "-", $document->getMimeType());
        $filename = "$mimeType.svg";

        $pathname = implode(DIRECTORY_SEPARATOR, [$this->getDirectory(), $filename]);
        if (false === file_exists($pathname)) {
            $filename = self::DEFAULT_ICON;
        }

        return $filename;
    }

    /**
     * Get the icon asset.
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the icon asset.
     */
    public function getIconAsset(DocumentInterface $document): string {
        $filename = $this->getIcon($document);
        return "/bundles/wbwedm/img/$filename";
    }
}
