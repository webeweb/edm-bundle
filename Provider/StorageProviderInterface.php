<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Provider;

use InvalidArgumentException;
use WBW\Bundle\CoreBundle\Provider\ProviderInterface;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;

/**
 * Storage provider interface.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider
 */
interface StorageProviderInterface extends ProviderInterface {

    /**
     * Tag name.
     *
     * @var string
     */
    const TAG_NAME = "wbw.edm.provider.storage";

    /**
     * Delete a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @return void
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a directory.
     */
    public function deleteDirectory(DocumentInterface $directory);

    /**
     * Delete a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a document.
     */
    public function deleteDocument(DocumentInterface $document);

    /**
     * Download a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @return DocumentInterface Returns the directory.
     */
    public function downloadDirectory(DocumentInterface $directory);

    /**
     * Download a document.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface Returns the document.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a document.
     */
    public function downloadDocument(DocumentInterface $document);

    /**
     * Move a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     */
    public function moveDocument(DocumentInterface $document);

    /**
     * Create a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @return void
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a directory.
     */
    public function newDirectory(DocumentInterface $directory);

    /**
     * Upload a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a document.
     */
    public function uploadDocument(DocumentInterface $document);
}
