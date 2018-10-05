<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Manager;

use Exception;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Exception\NoneRegisteredStorageProviderException;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;

/**
 * Storage manager test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Manager
 * @final
 */
final class StorageManagerTest extends AbstractFrameworkTestCase {

    /**
     * Directory.
     *
     * @var DocumentInterface
     */
    private $directory;

    /**
     * Directory event.
     *
     * @var DocumentEvent
     */
    private $directoryEvent;

    /**
     * Document.
     *
     * @var DocumentInterface
     */
    private $document;

    /**
     * Document event.
     *
     * @var DocumentEvent
     */
    private $documentEvent;

    /**
     * Storage provider.
     *
     * @var StorageProviderInterface
     */
    private $storageProvider;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set a Directory mock.
        $this->directory = new Document();
        $this->directory->setType(Document::TYPE_DIRECTORY);

        // Set a Directory event mock.
        $this->directoryEvent = new DocumentEvent("", $this->directory);

        // Set a Document mock.
        $this->document = new Document();
        $this->document->setType(Document::TYPE_DOCUMENT);

        // Set a Document event mock.
        $this->documentEvent = new DocumentEvent("", $this->document);

        // Set a Storage provider mock.
        $this->storageProvider = $this->getMockBuilder(StorageProviderInterface::class)->getMock();
        $this->storageProvider->expects($this->any())->method("downloadDocument")->willReturn(new Document());
        $this->storageProvider->expects($this->any())->method("readDocument")->willReturn(new Document());
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $obj = new StorageManager($this->objectManager);

        $this->assertSame($this->objectManager, $obj->getEntityManager());
        $this->assertCount(0, $obj->getProviders());
    }

    /**
     * Tests the downloadDocument() method.
     *
     * @return void
     */
    public function testDownloadDocument() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        $this->assertInstanceOf(DocumentInterface::class, $obj->downloadDocument($this->document));
    }

    /**
     * Tests the downloadDocument() method.
     *
     * @return void
     */
    public function testDownloadDocumentWithNoneRegisteredStorageProviderException() {

        $obj = new StorageManager($this->objectManager);

        try {

            $obj->downloadDocument($this->document);
        } catch (Exception $ex) {

            $this->assertInstanceOf(NoneRegisteredStorageProviderException::class, $ex);
            $this->assertEquals("None registered storage provider", $ex->getMessage());
        }
    }

    /**
     * Tests the onDeletedDirectory() method.
     *
     * @return void
     */
    public function testOnDeletedDirectory() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        $this->assertNull($obj->onDeletedDirectory($this->directoryEvent));
    }

    /**
     * Tests the onDeletedDirectory() method.
     *
     * @return void
     */
    public function testOnDeletedDirectoryWithIllegalArgumentException() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        try {

            $obj->onDeletedDirectory($this->documentEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a directory", $ex->getMessage());
        }
    }

    /**
     * Tests the onDeletedDirectory() method.
     *
     * @return void
     */
    public function testOnDeletedDirectoryWithNoneRegisteredStorageProviderException() {

        $obj = new StorageManager($this->objectManager);

        try {

            $obj->onDeletedDirectory($this->documentEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(NoneRegisteredStorageProviderException::class, $ex);
            $this->assertEquals("None registered storage provider", $ex->getMessage());
        }
    }

    /**
     * Tests the onDeletedDocument() method.
     *
     * @return void
     */
    public function testOnDeletedDocument() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        $this->assertNull($obj->onDeletedDocument($this->documentEvent));
    }

    /**
     * Tests the onDeletedDocument() method.
     *
     * @return void
     */
    public function testOnDeletedDocumentWithIllegalArgumentException() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        try {

            $obj->onDeletedDocument($this->directoryEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a document", $ex->getMessage());
        }
    }

    /**
     * Tests the onDeletedDocument() method.
     *
     * @return void
     */
    public function testOnDeletedDocumentWithNoneRegisteredStorageProviderException() {

        $obj = new StorageManager($this->objectManager);

        try {

            $obj->onDeletedDocument($this->documentEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(NoneRegisteredStorageProviderException::class, $ex);
            $this->assertEquals("None registered storage provider", $ex->getMessage());
        }
    }

    /**
     * Tests the onDownloadedDocument().
     *
     * @return void
     */
    public function testOnDownloadDocument() {

        $obj = new StorageManager($this->objectManager);

        $this->assertNull($obj->onDownloadedDocument($this->documentEvent));
    }

    /**
     * Tests the onMovedDocument() method.
     *
     * @return void
     */
    public function testOnMovedDocument() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        $this->assertNull($obj->onMovedDocument($this->documentEvent));
    }

    /**
     * Tests the onMovedDocument() method.
     *
     * @return void
     */
    public function testOnMovedDocumentWithIllegalArgumentException() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        try {

            $obj->onMovedDocument($this->documentEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a document", $ex->getMessage());
        }
    }

    /**
     * Tests the onMovedDocument() method.
     *
     * @return void
     */
    public function testOnMovedDocumentWithNoneRegisteredStorageProviderException() {

        $obj = new StorageManager($this->objectManager);

        try {

            $obj->onMovedDocument($this->documentEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(NoneRegisteredStorageProviderException::class, $ex);
            $this->assertEquals("None registered storage provider", $ex->getMessage());
        }
    }

    /**
     * Tests the onNewDirectory() method.
     *
     * @return void
     */
    public function testOnNewDirectory() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        $this->assertNull($obj->onNewDirectory($this->directoryEvent));
    }

    /**
     * Tests the onNewDirectory() method.
     *
     * @return void
     */
    public function testOnNewDirectoryWithIllegalArgumentException() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        try {

            $obj->onNewDirectory($this->documentEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a directory", $ex->getMessage());
        }
    }

    /**
     * Tests the onNewDirectory() method.
     *
     * @return void
     */
    public function testOnNewDirectoryWithNoneRegisteredStorageProviderException() {

        $obj = new StorageManager($this->objectManager);

        try {

            $obj->onNewDirectory($this->documentEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(NoneRegisteredStorageProviderException::class, $ex);
            $this->assertEquals("None registered storage provider", $ex->getMessage());
        }
    }

    /**
     * Tests the onUploadedDocument() method.
     *
     * @return void
     */
    public function testOnUploadedDocument() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        $this->assertNull($obj->onUploadedDocument($this->documentEvent));
    }

    /**
     * Tests the onUploadedDocument() method.
     *
     * @return void
     */
    public function testOnUploadedDocumentWithIllegalArgumentException() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        try {

            $obj->onUploadedDocument($this->documentEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a document", $ex->getMessage());
        }
    }

    /**
     * Tests the onUploadedDocument() method.
     *
     * @return void
     */
    public function testOnUploadedDocumentWithNoneRegisteredStorageProviderException() {

        $obj = new StorageManager($this->objectManager);

        try {

            $obj->onUploadedDocument($this->documentEvent);
        } catch (Exception $ex) {

            $this->assertInstanceOf(NoneRegisteredStorageProviderException::class, $ex);
            $this->assertEquals("None registered storage provider", $ex->getMessage());
        }
    }

    /**
     * Tests the readDocument() method.
     *
     * @return void
     */
    public function testReadDocument() {

        $obj = new StorageManager($this->objectManager);

        $obj->registerProvider($this->storageProvider);

        $this->assertInstanceOf(DocumentInterface::class, $obj->readDocument($this->document));
    }

    /**
     * Tests the readDocument() method.
     *
     * @return void
     */
    public function testReadDocumentWithNoneRegisteredStorageProviderException() {

        $obj = new StorageManager($this->objectManager);

        try {

            $obj->readDocument($this->document);
        } catch (Exception $ex) {

            $this->assertInstanceOf(NoneRegisteredStorageProviderException::class, $ex);
            $this->assertEquals("None registered storage provider", $ex->getMessage());
        }
    }

}
