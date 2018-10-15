<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Exception\NoneRegisteredStorageProviderException;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Manager\StorageManagerInterface;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;
use WBW\Library\Core\FileSystem\FileHelper;

/**
 * Storage manager.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Manager
 */
class StorageManager implements StorageManagerInterface {

    /**
     * Entity manager.
     *
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * Storage providers.
     *
     * @var StorageProviderInterface[]
     */
    private $providers;

    /**
     * Constructor.
     *
     * @param ObjectManager $entityManager The entity manager.
     */
    public function __construct(ObjectManager $entityManager) {
        $this->setEntityManager($entityManager);
        $this->setProviders([]);
    }

    /**
     * {@inheritdoc}
     */
    public function downloadDocument(DocumentInterface $document) {

        // Check the providers.
        $this->hasProviders();

        // Return the document.
        return $this->getProviders()[0]->downloadDocument($document);
    }

    /**
     * Get the entity manager.
     *
     * @return ObjectManager Returns the entity manager.
     */
    public function getEntityManager() {
        return $this->entityManager;
    }

    /**
     * Get the providers.
     *
     * @return StorageProviderInterface[] Returns the providers.
     */
    public function getProviders() {
        return $this->providers;
    }

    /**
     * Determines if the storage provider has storage manager.
     *
     * @return bool Returns true.
     * @throws NoneRegisteredStorageProviderException Throws a none registered storage provider exception.
     */
    private function hasProviders() {
        if (0 === count($this->getProviders())) {
            throw new NoneRegisteredStorageProviderException();
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onDeletedDirectory(DocumentEvent $event) {

        // Check the providers.
        $this->hasProviders();

        // Check the document type.
        if (false === $event->getDocument()->isDirectory()) {
            throw new IllegalArgumentException("The document must be a directory");
        }

        // Delete the directory.
        foreach ($this->getProviders() as $current) {
            $current->onDeletedDirectory($event->getDocument());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onDeletedDocument(DocumentEvent $event) {

        // Check the providers.
        $this->hasProviders();

        // Check the document type.
        if (false === $event->getDocument()->isDocument()) {
            throw new IllegalArgumentException("The document must be a document");
        }

        // Delete the document.
        foreach ($this->getProviders() as $current) {
            $current->onDeletedDocument($event->getDocument());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onDownloadedDocument(DocumentEvent $event) {

        // Get the document.
        $document = $event->getDocument();

        // Increment the number of downloads.
        $document->incrementNumberDownloads();

        // Update the entities.
        $this->getEntityManager()->persist($document);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function onMovedDocument(DocumentEvent $event) {

        // Check the providers.
        $this->hasProviders();

        // Get the document.
        $document = $event->getDocument();

        // Decrease the size.
        if (null !== $document->getParentBackedUp()) {
            foreach (DocumentHelper::getPaths($document->getParentBackedUp()) as $current) {
                $current->decreaseSize($document->getSize());
                $this->getEntityManager()->persist($current);
            }
        }

        // Increase the size.
        if (null !== $document->getParent()) {
            foreach (DocumentHelper::getPaths($document->getParent()) as $current) {
                $current->increaseSize($document->getSize());
                $this->getEntityManager()->persist($current);
            }
        }

        // Update the entities.
        $this->getEntityManager()->persist($document);
        $this->getEntityManager()->flush();

        // Move the document.
        foreach ($this->getProviders() as $current) {
            $current->onMovedDocument($event->getDocument());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onNewDirectory(DocumentEvent $event) {

        // Check the providers.
        $this->hasProviders();

        // Check the document type.
        if (false === $event->getDocument()->isDirectory()) {
            throw new IllegalArgumentException("The document must be a directory");
        }

        // Create the directory.
        foreach ($this->getProviders() as $current) {
            $current->onNewDirectory($event->getDocument());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onUploadedDocument(DocumentEvent $event) {

        // Check the providers.
        $this->hasProviders();

        if (false === $event->getDocument()->isDocument()) {
            throw new IllegalArgumentException("The document must be a document");
        }

        // Get the document.
        $document = $event->getDocument();

        // Check the document upload.
        if (null !== $document->getUpload()) {
            $document->setExtension($document->getUpload()->getClientOriginalExtension());
            $document->setMimeType($document->getUpload()->getClientMimeType());
            $document->setSize(FileHelper::getSize($document->getUpload()->getPathname()));
        }

        // Increase the size.
        if (null !== $document->getParent()) {
            foreach (DocumentHelper::getPaths($document->getParent()) as $current) {
                $current->increaseSize($document->getSize());
                $this->getEntityManager()->persist($current);
            }
        }

        // Update the entities.
        $this->getEntityManager()->persist($document);
        $this->getEntityManager()->flush();

        // Save the document.
        foreach ($this->getProviders() as $current) {
            $current->onUploadedDocument($event->getDocument());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function readDocument(DocumentInterface $document) {

        // Check the providers.
        $this->hasProviders();

        // Check the document type.
        if (false === $document->isDocument()) {
            throw new IllegalArgumentException("The document must be a document");
        }

        // Returns the content.
        return $this->getProviders()[0]->readDocument($document);
    }

    /**
     * Register a provider.
     *
     * @param StorageProviderInterface $provider The storage provider.
     * @return void
     */
    public function registerProvider(StorageProviderInterface $provider) {
        $this->providers[] = $provider;
    }

    /**
     * Set the entity manager.
     *
     * @param ObjectManager $entityManager The entity manager.
     * @return StorageManagerInterface Returns this storage manager.
     */
    protected function setEntityManager(ObjectManager $entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * Set the providers.
     *
     * @param StorageProviderInterface $providers The providers.
     * @return StorageManagerInterface Returns this storage manager.
     */
    protected function setProviders(array $providers) {
        $this->providers = $providers;
        return $this;
    }

}
