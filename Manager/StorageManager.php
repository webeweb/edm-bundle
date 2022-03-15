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

use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use WBW\Library\Symfony\Manager\AbstractManager;
use WBW\Library\Symfony\Manager\ManagerInterface;
use WBW\Library\Symfony\Provider\ProviderInterface;

/**
 * Storage manager.
 *
 * @author webeweb <https://github.com/webeweb>
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
     * Constructor.
     *
     * @param LoggerInterface $logger The logger.
     */
    public function __construct(LoggerInterface $logger) {
        parent::__construct($logger);
    }

    /**
     * {@inheritDoc}
     */
    public function addProvider(ProviderInterface $provider): ManagerInterface {

        if (false === ($provider instanceof StorageProviderInterface)) {
            throw new InvalidArgumentException("The provider must implements StorageProviderInterface");
        }

        return parent::addProvider($provider);
    }

    /**
     * Delete a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function deleteDirectory(DocumentInterface $directory): void {

        DocumentHelper::isDirectory($directory);

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->deleteDirectory($directory);
        }
    }

    /**
     * Delete a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function deleteDocument(DocumentInterface $document): void {

        DocumentHelper::isDocument($document);

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->deleteDocument($document);
        }
    }

    /**
     * Download a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @return Response|null Returns the response in case of success, null otherwise.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function downloadDirectory(DocumentInterface $directory): ?Response {

        DocumentHelper::isDirectory($directory);
        if (false === $this->hasProviders()) {
            return null;
        }

        /** @var StorageProviderInterface $provider */
        $provider = $this->getProviders()[0];

        return $provider->downloadDirectory($directory);
    }

    /**
     * Download a document.
     *
     * @param DocumentInterface $document The document.
     * @return Response|null Returns the response in case of success, null otherwise.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function downloadDocument(DocumentInterface $document): ?Response {

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
    public function moveDocument(DocumentInterface $document): void {

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->moveDocument($document);
        }
    }

    /**
     * Create a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @param void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function newDirectory(DocumentInterface $directory): void {

        DocumentHelper::isDirectory($directory);

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->newDirectory($directory);
        }
    }

    /**
     * Upload a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function uploadDocument(DocumentInterface $document): void {

        DocumentHelper::isDocument($document);

        /** @var StorageProviderInterface $current */
        foreach ($this->getProviders() as $current) {
            $current->uploadDocument($document);
        }
    }
}
