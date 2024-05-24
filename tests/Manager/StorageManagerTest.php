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

use InvalidArgumentException;
use Throwable;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Library\Symfony\Provider\ProviderInterface;

/**
 * Storage manager test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Manager
 */
class StorageManagerTest extends AbstractTestCase {

    /**
     * Test addProvider()
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
     * Test addProvider()
     *
     * @return void
     */
    public function testAddProviderWithInvalidArgumentException(): void {

        // Set a Provider mock.
        $provider = $this->getMockBuilder(ProviderInterface::class)->getMock();

        $obj = new StorageManager($this->logger);

        try {
            $obj->addProvider($provider);
        } catch (Throwable $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
            $this->assertEquals("The provider must implements " . StorageProviderInterface::class, $ex->getMessage());
        }
    }

    /**
     * Test deleteDirectory()
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testDeleteDirectory(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->deleteDirectory($this->directory));
    }

    /**
     * Test deleteDirectory()
     *
     * @return void
     */
    public function testDeleteDirectoryWithInvalidArgumentException(): void {

        $obj = $this->storageManager;

        try {

            $obj->deleteDirectory($this->document);
        } catch (Throwable $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Test deleteDocument()
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testDeleteDocument(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->deleteDocument($this->document));
    }

    /**
     * Test deleteDocument()
     *
     * @return void
     */
    public function testDeleteDocumentWithInvalidArgumentException(): void {

        $obj = $this->storageManager;

        try {

            $obj->deleteDocument($this->directory);
        } catch (Throwable $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Test the downloadedDirectory().
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testDownloadDirectory(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->downloadDirectory($this->directory));
    }

    /**
     * Test the downloadedDirectory().
     *
     * @return void
     */
    public function testDownloadDirectoryWithInvalidArgumentException(): void {

        $obj = $this->storageManager;

        try {

            $obj->downloadDirectory($this->document);
        } catch (Throwable $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Test the downloadedDirectory().
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testDownloadDirectoryWithoutProvider(): void {

        $obj = new StorageManager($this->logger);

        $this->assertNull($obj->downloadDirectory($this->directory));
    }

    /**
     * Test downloadDocument()
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testDownloadDocument(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->downloadDocument($this->document));
    }

    /**
     * Test the downloadedDocument().
     *
     * @return void
     */
    public function testDownloadDocumentWithInvalidArgumentException(): void {

        $obj = $this->storageManager;

        try {

            $obj->downloadDocument($this->directory);
        } catch (Throwable $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Test downloadDocument()
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testDownloadDocumentWithoutProvider(): void {

        $obj = new StorageManager($this->logger);

        $this->assertNull($obj->downloadDocument($this->document));
    }

    /**
     * Test moveDocument()
     *
     * @return void
     */
    public function testMoveDocument(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->moveDocument($this->document));
    }

    /**
     * Test newDirectory()
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testNewDirectory(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->newDirectory($this->directory));
    }

    /**
     * Test newDirectory()
     *
     * @return void
     */
    public function testNewDirectoryWithInvalidArgumentException(): void {

        $obj = $this->storageManager;

        try {

            $obj->newDirectory($this->document);
        } catch (Throwable $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Test uploadDocument()
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testUploadedDocument(): void {

        $obj = $this->storageManager;

        $this->assertNull($obj->uploadDocument($this->document));
    }

    /**
     * Test uploadDocument()
     *
     * @return void
     */
    public function testUploadedDocumentWithInvalidArgumentException(): void {

        $obj = $this->storageManager;

        try {

            $obj->uploadDocument($this->directory);
        } catch (Throwable $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Test __construct()
     *
     * @return void
     */
    public function test__construct(): void {

        $this->assertSame("wbw.edm.manager.storage", StorageManager::SERVICE_NAME);
    }
}
