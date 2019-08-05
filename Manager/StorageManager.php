<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Manager;

use InvalidArgumentException;
use WBW\Bundle\CoreBundle\Manager\AbstractManager;
use WBW\Bundle\CoreBundle\Provider\ProviderInterface;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;

/**
 * Storage manager.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Manager
 */
class StorageManager extends AbstractManager {

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "wbw.edm.manager.storage";

    /**
     * {@inheritDoc}
     */
    public function addProvider(ProviderInterface $provider) {
        if (false === ($provider instanceof StorageProviderInterface)) {
            throw new InvalidArgumentException("The provider must implements StorageProviderInterface");
        }
        return parent::addProvider($provider);
    }

    /**
     * Delete a directory.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a directory.
     */
    public function deleteDirectory(DocumentInterface $document) {

        DocumentHelper::isDirectory($document);

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->deleteDirectory($document);
        }
    }

    /**
     * Delete a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a document.
     */
    public function deleteDocument(DocumentInterface $document) {

        DocumentHelper::isDocument($document);

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->deleteDocument($document);
        }
    }

    /**
     * Download a directory.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface Returns the document.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a directory.
     */
    public function downloadDirectory(DocumentInterface $document) {

        DocumentHelper::isDirectory($document);
        if (false === $this->hasProviders()) {
            return null;
        }

        /** @var StorageProviderInterface $provider */
        $provider = $this->getProviders()[0];

        return $provider->downloadDirectory($document);
    }

    /**
     * Download a document.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface Returns the document.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a document.
     */
    public function downloadDocument(DocumentInterface $document) {

        DocumentHelper::isDocument($document);
        if (false === $this->hasProviders()) {
            return null;
        }

        /** @var StorageProviderInterface $provider */
        $provider = $this->getProviders()[0];

        return $provider->downloadDocument($document);
    }

    /**
     * Move a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     */
    public function moveDocument(DocumentInterface $document) {

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->moveDocument($document);
        }
    }

    /**
     * Create a directory.
     *
     * @param DocumentInterface $document The document.
     * @param void
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a directory.
     */
    public function newDirectory(DocumentInterface $document) {

        DocumentHelper::isDirectory($document);

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->newDirectory($document);
        }
    }

    /**
     * On uploaded document.
     *
     * @param DocumentInterface $document The document.
     * @return void.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a document.
     */
    public function uploadDocument(DocumentInterface $document) {

        DocumentHelper::isDocument($document);

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->uploadDocument($document);
        }
    }
}
