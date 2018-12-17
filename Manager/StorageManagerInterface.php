<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Manager;

use WBW\Bundle\EDMBundle\Entity\DocumentInterface;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Exception\NoneRegisteredStorageProviderException;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;

/**
 * Storage manager interface.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Manager
 */
interface StorageManagerInterface {

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "webeweb.edm.manager.storage";

    /**
     * Download a document.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface Returns the document.
     * @throws NoneRegisteredStorageProviderException Throws a none registered storage provider exception.
     */
    public function downloadDocument(DocumentInterface $document);

    /**
     * On deleted directory.
     *
     * @param DocumentEvent $event The event.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a directory.
     * @throws NoneRegisteredStorageProviderException Throws a none registered storage provider exception.
     */
    public function onDeletedDirectory(DocumentEvent $event);

    /**
     * On deleted document.
     *
     * @param DocumentEvent $event The event.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
     * @throws NoneRegisteredStorageProviderException Throws a none registered storage provider exception.
     */
    public function onDeletedDocument(DocumentEvent $event);

    /**
     * On downloaded document.
     *
     * @param DocumentEvent $event The event.
     * @return void
     */
    public function onDownloadedDocument(DocumentEvent $event);

    /**
     * On moved document.
     *
     * @param DocumentEvent $event The event.
     * @return void
     * @throws NoneRegisteredStorageProviderException Throws a none registered storage provider exception.
     */
    public function onMovedDocument(DocumentEvent $event);

    /**
     * On new directory.
     *
     * @param DocumentEvent $event The event.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a directory.
     * @throws NoneRegisteredStorageProviderException Throws a none registered storage provider exception.
     */
    public function onNewDirectory(DocumentEvent $event);

    /**
     * On uploaded document.
     *
     * @param DocumentEvent $event The event.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
     * @throws NoneRegisteredStorageProviderException Throws a none registered storage provider exception.
     */
    public function onUploadedDocument(DocumentEvent $event);

    /**
     * Read a document.
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the document content.
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
     * @throws NoneRegisteredStorageProviderException Throws a none registered storage provider exception.
     */
    public function readDocument(DocumentInterface $document);
}
