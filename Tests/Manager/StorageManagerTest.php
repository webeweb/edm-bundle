<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Manager;

use Exception;
use InvalidArgumentException;
use WBW\Bundle\CoreBundle\Provider\ProviderInterface;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Model\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Storage manager test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Manager
 */
class StorageManagerTest extends AbstractTestCase {

    /**
     * Directory.
     *
     * @var DocumentInterface
     */
    private $directory;

    /**
     * Document.
     *
     * @var DocumentInterface
     */
    private $document;

    /**
     * Storage manager.
     *
     * @var StorageManager
     */
    private $storageManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set a Document mock.
        $this->document = new Document();
        $this->document->setType(Document::TYPE_DOCUMENT);

        // Set a Directory mock.
        $this->directory = new Document();
        $this->directory->setType(Document::TYPE_DIRECTORY);

        // Set a Storage provider mock.
        $storageProvider = $this->getMockBuilder(StorageProviderInterface::class)->getMock();

        // Set a Storage manager mock.
        $this->storageManager = new StorageManager();
        $this->storageManager->addProvider($storageProvider);
    }

    /**
     * Tests the addProvider() method.
     *
     * @return void
     */
    public function testAddProvider() {

        // Set a Storage provider mock.
        $storageProvider = $this->getMockBuilder(StorageProviderInterface::class)->getMock();

        $obj = new StorageManager();

        $obj->addProvider($storageProvider);
        $this->assertSame($storageProvider, $obj->getProviders()[0]);
    }

    /**
     * Tests the addProvider() method.
     *
     * @return void
     */
    public function testAddProviderWithInvalidArgumentException() {

        // Set a Provider mock.
        $provider = $this->getMockBuilder(ProviderInterface::class)->getMock();

        $obj = new StorageManager();

        try {
            $obj->addProvider($provider);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
            $this->assertEquals("The provider must implements StorageProviderInterface", $ex->getMessage());
        }
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $this->assertSame("wbw.edm.manager.storage", StorageManager::SERVICE_NAME);
    }

    /**
     * Tests the deleteDirectory() method.
     *
     * @return void
     */
    public function testDeleteDirectory() {

        $obj = $this->storageManager;

        $this->assertNull($obj->deleteDirectory($this->directory));
    }

    /**
     * Tests the deleteDirectory() method.
     *
     * @return void
     */
    public function testDeleteDirectoryWithInvalidArgumentException() {

        $obj = $this->storageManager;

        try {

            $obj->deleteDirectory($this->document);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Tests the deleteDocument() method.
     *
     * @return void
     */
    public function testDeleteDocument() {

        $obj = $this->storageManager;

        $this->assertNull($obj->deleteDocument($this->document));
    }

    /**
     * Tests the deleteDocument() method.
     *
     * @return void
     */
    public function testDeleteDocumentWithInvalidArgumentException() {

        $obj = $this->storageManager;

        try {

            $obj->deleteDocument($this->directory);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Tests the downloadedDirectory().
     *
     * @return void
     */
    public function testDownloadDirectory() {

        $obj = $this->storageManager;

        $this->assertNull($obj->downloadDirectory($this->directory));
    }

    /**
     * Tests the downloadedDirectory().
     *
     * @return void
     */
    public function testDownloadDirectoryWithInvalidArgumentException() {

        $obj = $this->storageManager;

        try {

            $obj->downloadDirectory($this->document);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Tests the downloadedDirectory().
     *
     * @return void
     */
    public function testDownloadDirectoryWithoutProvider() {

        $obj = new StorageManager();

        $this->assertNull($obj->downloadDirectory($this->directory));
    }

    /**
     * Tests the downloadDocument() method.
     *
     * @return void
     */
    public function testDownloadDocument() {

        $obj = $this->storageManager;

        $this->assertNull($obj->downloadDocument($this->document));
    }

    /**
     * Tests the downloadedDocument().
     *
     * @return void
     */
    public function testDownloadDocumentWithInvalidArgumentException() {

        $obj = $this->storageManager;

        try {

            $obj->downloadDocument($this->directory);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Tests the downloadDocument() method.
     *
     * @return void
     */
    public function testDownloadDocumentWithoutProvider() {

        $obj = new StorageManager();

        $this->assertNull($obj->downloadDocument($this->document));
    }

    /**
     * Tests the moveDocument() method.
     *
     * @return void
     */
    public function testMoveDocument() {

        $obj = $this->storageManager;

        $this->assertNull($obj->moveDocument($this->document));
    }

    /**
     * Tests the newDirectory() method.
     *
     * @return void
     */
    public function testNewDirectory() {

        $obj = $this->storageManager;

        $this->assertNull($obj->newDirectory($this->directory));
    }

    /**
     * Tests the newDirectory() method.
     *
     * @return void
     */
    public function testNewDirectoryWithInvalidArgumentException() {

        $obj = $this->storageManager;

        try {

            $obj->newDirectory($this->document);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Tests the uploadDocument() method.
     *
     * @return void
     */
    public function testUploadedDocument() {

        $obj = $this->storageManager;

        $this->assertNull($obj->uploadDocument($this->document));
    }

    /**
     * Tests the uploadDocument() method.
     *
     * @return void
     */
    public function testUploadedDocumentWithInvalidArgumentException() {

        $obj = $this->storageManager;

        try {

            $obj->uploadDocument($this->directory);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }
}
