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
     * Tests the addProvider() method.
     *
     * @return void
     */
    public function testAddProvider(): void {

        // Set a Storage provider mock.
        $storageProvider = $this->getMockBuilder(StorageProviderInterface::class)->getMock();

        $obj = new StorageManager($this->logger);

        $obj->addProvider($storageProvider);
        $this->assertSame($storageProvider, $obj->getProviders()[0]);
    }

    /**
     * Tests the addProvider() method.
     *
     * @return void
     */
    public function testAddProviderWithInvalidArgumentException(): void {

        // Set a Provider mock.
        $provider = $this->getMockBuilder(ProviderInterface::class)->getMock();

        $obj = new StorageManager($this->logger);

        try {
            $obj->addProvider($provider);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
            $this->assertEquals("The provider must implements StorageProviderInterface", $ex->getMessage());
        }
    }

    /**
     * Tests the deleteDirectory() method.
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testDeleteDirectory(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->deleteDirectory($this->directory));
    }

    /**
     * Tests the deleteDirectory() method.
     *
     * @return void
     */
    public function testDeleteDirectoryWithInvalidArgumentException(): void {

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
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testDeleteDocument(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->deleteDocument($this->document));
    }

    /**
     * Tests the deleteDocument() method.
     *
     * @return void
     */
    public function testDeleteDocumentWithInvalidArgumentException(): void {

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
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testDownloadDirectory(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->downloadDirectory($this->directory));
    }

    /**
     * Tests the downloadedDirectory().
     *
     * @return void
     */
    public function testDownloadDirectoryWithInvalidArgumentException(): void {

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
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testDownloadDirectoryWithoutProvider(): void {

        $obj = new StorageManager($this->logger);

        $this->assertNull($obj->downloadDirectory($this->directory));
    }

    /**
     * Tests the downloadDocument() method.
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testDownloadDocument(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->downloadDocument($this->document));
    }

    /**
     * Tests the downloadedDocument().
     *
     * @return void
     */
    public function testDownloadDocumentWithInvalidArgumentException(): void {

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
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testDownloadDocumentWithoutProvider(): void {

        $obj = new StorageManager($this->logger);

        $this->assertNull($obj->downloadDocument($this->document));
    }

    /**
     * Tests the moveDocument() method.
     *
     * @return void
     */
    public function testMoveDocument(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->moveDocument($this->document));
    }

    /**
     * Tests the newDirectory() method.
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testNewDirectory(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->newDirectory($this->directory));
    }

    /**
     * Tests the newDirectory() method.
     *
     * @return void
     */
    public function testNewDirectoryWithInvalidArgumentException(): void {

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
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testUploadedDocument(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->uploadDocument($this->document));
    }

    /**
     * Tests the uploadDocument() method.
     *
     * @return void
     */
    public function testUploadedDocumentWithInvalidArgumentException(): void {

        $obj = $this->storageManager;

        try {

            $obj->uploadDocument($this->directory);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function test__construct(): void {

        $this->assertSame("wbw.edm.manager.storage", StorageManager::SERVICE_NAME);
    }
}
