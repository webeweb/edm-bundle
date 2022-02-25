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
use Exception;
use WBW\Bundle\CoreBundle\Service\ObjectManagerTrait;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Manager\StorageManagerTrait;

/**
 * Document event listener.
 *
 * @author webeweb <https://github.com/webeweb>
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
     * On delete document.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function onDeleteDocument(DocumentEvent $event): DocumentEvent {

        if (true === $event->getDocument()->isDirectory()) {
            $this->getStorageManager()->deleteDirectory($event->getDocument());
        } else {
            $this->getStorageManager()->deleteDocument($event->getDocument());
        }

        DocumentHelper::decreaseSize($event->getDocument()->getSize(), $event->getDocument()->getParent());
        $this->getObjectManager()->flush();

        return $event;
    }

    /**
     * On download document.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function onDownloadDocument(DocumentEvent $event): DocumentEvent {

        if (true === $event->getDocument()->isDirectory()) {
            $response = $this->getStorageManager()->downloadDirectory($event->getDocument());
        } else {
            $response = $this->getStorageManager()->downloadDocument($event->getDocument());
        }

        if (null !== $response) {
            $event->getDocument()->incrementNumberDownloads();
        }

        $this->getObjectManager()->flush();

        return $event->setResponse($response);
    }

    /**
     * On move document.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     */
    public function onMoveDocument(DocumentEvent $event): DocumentEvent {

        $this->getStorageManager()->moveDocument($event->getDocument());

        DocumentHelper::decreaseSize($event->getDocument()->getSize(), $event->getDocument()->getSavedParent());
        DocumentHelper::increaseSize($event->getDocument()->getSize(), $event->getDocument()->getParent());
        $this->getObjectManager()->flush();

        return $event;
    }

    /**
     * On new document.
     *
     * @param DocumentEvent $event The event.
     * @return DocumentEvent Returns the event.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function onNewDocument(DocumentEvent $event): DocumentEvent {

        if (true === $event->getDocument()->isDocument()) {
            $this->getStorageManager()->uploadDocument($event->getDocument());
        } else {
            $this->getStorageManager()->newDirectory($event->getDocument());
        }

        DocumentHelper::increaseSize($event->getDocument()->getSize(), $event->getDocument()->getParent());
        $this->getObjectManager()->flush();

        return $event;
    }
}
