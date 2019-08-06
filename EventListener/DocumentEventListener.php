<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use InvalidArgumentException;
use WBW\Bundle\CoreBundle\Service\ObjectManagerTrait;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Manager\StorageManagerTrait;

/**
 * Document event listener.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\EventListener
 */
class DocumentEventListener {

    use ObjectManagerTrait;
    use StorageManagerTrait;

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "wbw.edm.event_listener.document";

    /**
     * Constructor.
     *
     * @param ObjectManager $objectManager The object manager.
     * @param StorageManager $storageManager The storage manager.
     */
    public function __construct(ObjectManager $objectManager, StorageManager $storageManager) {
        $this->setObjectManager($objectManager);
        $this->setStorageManager($storageManager);
    }

    /**
     * On delete a directory.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a directory.
     */
    public function onDeleteDirectory(DocumentEvent $event) {
        $this->getStorageManager()->deleteDirectory($event->getDocument());
        return $event;
    }

    /**
     * On delete a document.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a document.
     */
    public function onDeleteDocument(DocumentEvent $event) {
        $this->getStorageManager()->deleteDocument($event->getDocument());
        return $event;
    }

    /**
     * On download a directory.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a directory.
     */
    public function onDownloadDirectory(DocumentEvent $event) {
        $response = $this->getStorageManager()->downloadDirectory($event->getDocument());
        return $event->setResponse($response);
    }

    /**
     * On download a document.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a document.
     */
    public function onDownloadDocument(DocumentEvent $event) {
        $response = $this->getStorageManager()->downloadDocument($event->getDocument());
        return $event->setResponse($response);
    }

    /**
     * On move a document.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     */
    public function onMoveDocument(DocumentEvent $event) {
        $this->getStorageManager()->moveDocument($event->getDocument());
        return $event;
    }

    /**
     * On new directory.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a directory.
     */
    public function onNewDirectory(DocumentEvent $event) {
        $this->getStorageManager()->newDirectory($event->getDocument());
        return $event;
    }

    /**
     * On uploaded document.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is not a document.
     */
    public function onUploadDocument(DocumentEvent $event) {
        $this->getStorageManager()->uploadDocument($event->getDocument());
        return $event;
    }
}
