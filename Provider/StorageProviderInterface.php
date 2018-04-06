<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Provider;

use WBW\Bundle\EDMBundle\Entity\DocumentInterface;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;

/**
 * Storage provider interface.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider
 */
interface StorageProviderInterface {

    /**
     * Tag name.
     *
     * @var string
     */
    const TAG_NAME = "edm.storage.provider";

    /**
     * Download a document.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface Returns the document.
     */
    public function downloadDocument(DocumentInterface $document);

    /**
     * On deleted directory.
     *
     * @param DocumentInterface $event The event.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a directory.
     */
    public function onDeletedDirectory(DocumentInterface $event);

    /**
     * On deleted document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
     */
    public function onDeletedDocument(DocumentInterface $document);

    /**
     * On moved document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     */
    public function onMovedDocument(DocumentInterface $document);

    /**
     * On new directory.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a directory.
     */
    public function onNewDirectory(DocumentInterface $document);

    /**
     * On uploaded document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
     */
    public function onUploadedDocument(DocumentInterface $document);

    /**
     * Read a document.
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the document content.
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
     */
    public function readDocument(DocumentInterface $document);
}
