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

use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\EventListener\DocumentEventListener;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Document event listener test.
 *
 * @author webeweb <https://github.com/webeweb/>
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
    protected function setUp() {
        parent::setUp();

        // Set a Directory event mock.
        $this->directoryEvent = new DocumentEvent("", $this->directory);

        // Set a Document event mock.
        $this->documentEvent = new DocumentEvent("", $this->document);
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $this->assertSame("wbw.edm.event_listener.document", DocumentEventListener::SERVICE_NAME);

        $obj = new DocumentEventListener($this->objectManager, $this->storageManager);

        $this->assertSame($this->objectManager, $obj->getObjectManager());
        $this->assertSame($this->storageManager, $obj->getStorageManager());
    }

    /**
     * Tests the onDeleteDirectory() method.
     *
     * @return void
     */
    public function testOnDeleteDirectory() {

        $obj = new DocumentEventListener($this->objectManager, $this->storageManager);

        $res = $obj->onDeleteDirectory($this->directoryEvent);
        $this->assertSame($res, $this->directoryEvent);
    }

    /**
     * Tests the onDeleteDocument() method.
     *
     * @return void
     */
    public function testOnDeleteDocument() {

        $obj = new DocumentEventListener($this->objectManager, $this->storageManager);

        $res = $obj->onDeleteDocument($this->documentEvent);
        $this->assertSame($res, $this->documentEvent);
    }

    /**
     * Tests the onDownloadDirectory() method.
     *
     * @return void
     */
    public function testOnDownloadDirectory() {

        $obj = new DocumentEventListener($this->objectManager, $this->storageManager);

        $res = $obj->onDownloadDirectory($this->directoryEvent);
        $this->assertSame($res, $this->directoryEvent);
    }

    /**
     * Tests the onDownloadDocument() method.
     *
     * @return void
     */
    public function testOnDownloadDocument() {

        $obj = new DocumentEventListener($this->objectManager, $this->storageManager);

        $res = $obj->onDownloadDocument($this->documentEvent);
        $this->assertSame($res, $this->documentEvent);
    }

    /**
     * Tests the onMoveDocument() method.
     *
     * @return void
     */
    public function testOnMoveDocument() {

        $obj = new DocumentEventListener($this->objectManager, $this->storageManager);

        $res = $obj->onMoveDocument($this->documentEvent);
        $this->assertSame($res, $this->documentEvent);
    }

    /**
     * Tests the onNewDirectory() method.
     *
     * @return void
     */
    public function testOnNewDirectory() {

        $obj = new DocumentEventListener($this->objectManager, $this->storageManager);

        $res = $obj->onNewDirectory($this->directoryEvent);
        $this->assertSame($res, $this->directoryEvent);
    }

    /**
     * Tests the onUploadDocument() method.
     *
     * @return void
     */
    public function testOnUploadDocument() {

        $obj = new DocumentEventListener($this->objectManager, $this->storageManager);

        $res = $obj->onUploadDocument($this->documentEvent);
        $this->assertSame($res, $this->documentEvent);
    }
}
