<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\EventListener;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\EventListener\DocumentEventListener;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Document event listener test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\EventListener
 */
class DocumentEventListenerTest extends AbstractTestCase {

    /**
     * Directory event.
     *
     * @var DocumentEvent
     */
    private $directoryEvent;

    /**
     * Document event.
     *
     * @var DocumentEvent
     */
    private $documentEvent;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void {
        parent::setUp();

        // Set a Directory event mock.
        $this->directoryEvent = new DocumentEvent("", $this->directory);

        // Set a Document event mock.
        $this->documentEvent = new DocumentEvent("", $this->document);
    }

    /**
     * Tests onDeleteDocument()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testOnDeleteDocument(): void {

        $obj = new DocumentEventListener($this->entityManager, $this->storageManager);

        $res = $obj->onDeleteDocument($this->documentEvent);
        $this->assertSame($res, $this->documentEvent);
    }

    /**
     * Tests onDeleteDocument()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testOnDeleteDocumentWithDirectory(): void {

        $obj = new DocumentEventListener($this->entityManager, $this->storageManager);

        $res = $obj->onDeleteDocument($this->directoryEvent);
        $this->assertSame($res, $this->directoryEvent);
    }

    /**
     * Tests onDownloadDocument()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testOnDownloadDocument(): void {

        // Set a Storage provider mock.
        $storageProvider = $this->getMockBuilder(StorageProviderInterface::class)->getMock();
        $storageProvider->expects($this->any())->method("downloadDocument")->willReturn(new Response());

        $this->storageManager->addProvider($storageProvider);

        $obj = new DocumentEventListener($this->entityManager, $this->storageManager);

        $res = $obj->onDownloadDocument($this->documentEvent);
        $this->assertSame($res, $this->documentEvent);
    }

    /**
     * Tests onDownloadDirectory()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testOnDownloadDocumentWithDirectory(): void {

        // Set a Storage provider mock.
        $storageProvider = $this->getMockBuilder(StorageProviderInterface::class)->getMock();
        $storageProvider->expects($this->any())->method("downloadDirectory")->willReturn(new Response());

        $this->storageManager->addProvider($storageProvider);

        $obj = new DocumentEventListener($this->entityManager, $this->storageManager);

        $res = $obj->onDownloadDocument($this->directoryEvent);
        $this->assertSame($res, $this->directoryEvent);
    }

    /**
     * Tests onMoveDocument()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testOnMoveDocument(): void {

        $obj = new DocumentEventListener($this->entityManager, $this->storageManager);

        $res = $obj->onMoveDocument($this->documentEvent);
        $this->assertSame($res, $this->documentEvent);
    }

    /**
     * Tests onNewDocument()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testOnNewDocument(): void {

        // Set the Document mock.
        $this->document->setParent($this->directory);

        $obj = new DocumentEventListener($this->entityManager, $this->storageManager);

        $res = $obj->onNewDocument($this->documentEvent);
        $this->assertSame($res, $this->documentEvent);
    }

    /**
     * Tests onNewDocument()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testOnNewDocumentWithDirectory(): void {

        $obj = new DocumentEventListener($this->entityManager, $this->storageManager);

        $res = $obj->onNewDocument($this->directoryEvent);
        $this->assertSame($res, $this->directoryEvent);
    }

    /**
     * Tests __construct()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function test__construct(): void {

        $this->assertSame("wbw.edm.event_listener.document", DocumentEventListener::SERVICE_NAME);

        $obj = new DocumentEventListener($this->entityManager, $this->storageManager);

        $this->assertSame($this->entityManager, $obj->getEntityManager());
        $this->assertSame($this->storageManager, $obj->getStorageManager());
    }
}
