<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Provider\Storage;

use Exception;
use FilesystemIterator;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\StreamedResponse;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\Storage\FilesystemStorageProvider;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Model\TestDocument;

/**
 * Filesystem storage provider test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider\Storage
 */
class FilesystemStorageProviderTest extends AbstractTestCase {

    /**
     * {@inheritDoc}
     */
    protected function setUp() {
        parent::setUp();

        // Clean up.
        $iterator = new FilesystemIterator($this->storageProviderDirectory);

        /** @var SplFileInfo $current */
        foreach ($iterator as $current) {
            if (".gitkeep" === $current->getFilename()) {
                continue;
            }
            (new Filesystem())->remove($current->getPathname());
        }
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $this->assertEquals("wbw.edm.provider.storage.filesystem", FilesystemStorageProvider::SERVICE_NAME);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        $this->assertEquals($this->storageProviderDirectory, $obj->getDirectory());
        $this->assertSame($this->logger, $obj->getLogger());
    }

    /**
     * Tests the deleteDirectory() method.
     *
     * @return void
     */
    public function testDeleteDirectory() {

        // Set a filename.
        $filename = implode(DIRECTORY_SEPARATOR, [$this->storageProviderDirectory, "1"]);

        // Create the filename.
        (new Filesystem())->mkdir($filename);
        $this->assertFileExists($filename);

        // Set a Document mock.
        $directory = new TestDocument();
        $directory->setId(1);
        $directory->setType(DocumentInterface::TYPE_DIRECTORY);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        $obj->deleteDirectory($directory);
        $this->assertFileNotExists($filename);
    }

    /**
     * Tests the deleteDirectory() method.
     *
     * @return void
     */
    public function testDeleteDirectoryWithDocument() {

        // Set a Document mock.
        $document = new Document();
        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        try {

            $obj->deleteDirectory($document);
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

        // Set a filename.
        $filename = implode(DIRECTORY_SEPARATOR, [$this->storageProviderDirectory, "1"]);

        // Create the filename.
        (new Filesystem())->touch($filename);
        $this->assertFileExists($filename);

        // Set a Document mock.
        $document = new TestDocument();
        $document->setId(1);
        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        $obj->deleteDocument($document);
        $this->assertFileNotExists($filename);
    }

    /**
     * Tests the deleteDocument() method.
     *
     * @return void
     */
    public function testDeleteDocumentWithDirectory() {

        // Set a Document mock.
        $directory = new Document();
        $directory->setType(DocumentInterface::TYPE_DIRECTORY);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        try {

            $obj->deleteDocument($directory);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Tests the downloadDocument() method.
     *
     * @return void
     */
    public function testDownloadDirectory() {

        // Set a Document mock.
        $directory = new Document();
        $directory->setName("directory");
        $directory->setType(DocumentInterface::TYPE_DIRECTORY);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        $res = $obj->downloadDirectory($directory);
        $this->assertInstanceOf(StreamedResponse::class, $res);

        $this->assertContains("content-disposition", $res->headers->keys());
        $this->assertContains("content-type", $res->headers->keys());

        $this->assertRegExp('/^attachement;\ filename="[0-9\.\-]{16}_directory\.zip"$/', $res->headers->get("content-disposition"));
        $this->assertEquals("application/zip", $res->headers->get("content-type"));
        $this->assertEquals(200, $res->getStatusCode());
    }

    /**
     * Tests the downloadDocument() method.
     *
     * @return void
     */
    public function testDownloadDocument() {

        // Set a Document mock.
        $document = new Document();
        $document->setExtension("php");
        $document->setMimeType("text/php");
        $document->setName("document");

        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        $res = $obj->downloadDocument($document);
        $this->assertInstanceOf(StreamedResponse::class, $res);

        $this->assertContains("content-disposition", $res->headers->keys());
        $this->assertContains("content-type", $res->headers->keys());

        $this->assertRegExp('/^attachement;\ filename="[0-9\.\-]{16}_document\.php"$/', $res->headers->get("content-disposition"));
        $this->assertEquals("text/php", $res->headers->get("content-type"));
        $this->assertEquals(200, $res->getStatusCode());
    }

    /**
     * Tests the moveDocument() method.
     *
     * @return void
     */
    public function testMoveDocument() {

        // Set the filenames.
        $filename1 = implode(DIRECTORY_SEPARATOR, [$this->storageProviderDirectory, "1"]);
        $filename2 = implode(DIRECTORY_SEPARATOR, [$this->storageProviderDirectory, "2"]);

        // Create the filenames.
        (new Filesystem())->mkdir($filename1);
        (new Filesystem())->touch($filename2);
        $this->assertFileExists($filename1);
        $this->assertFileExists($filename2);

        // Set a Document mock.
        $directory = new TestDocument();
        $directory->setId(1);
        $directory->setType(DocumentInterface::TYPE_DIRECTORY);

        $document = new TestDocument();
        $document->setId(2);
        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        $document->saveParent();
        $document->setParent($directory);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        $obj->moveDocument($document);
        $this->assertFileNotExists($filename2);
        $this->assertFileExists($filename1);
        $this->assertFileExists(implode(DIRECTORY_SEPARATOR, [$filename1, "2"]));
    }

    /**
     * Tests the newDirectory() method.
     *
     * @return void
     */
    public function testNewDirectory() {

        // Set a filename.
        $filename = implode(DIRECTORY_SEPARATOR, [$this->storageProviderDirectory, "1"]);

        // Set a Document mock.
        $directory = new TestDocument();
        $directory->setId(1);
        $directory->setType(DocumentInterface::TYPE_DIRECTORY);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        $obj->newDirectory($directory);
        $this->assertFileExists($filename);
    }

    /**
     * Tests the newDirectory() method.
     *
     * @return void
     */
    public function testNewDirectoryWithDocument() {

        // Set a Document mock.
        $document = new Document();
        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        try {

            $obj->newDirectory($document);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }

    /**
     * Tests the uploadDocument() method.
     *
     * @return void
     */
    public function testUploadDocument() {

        // Set a filename.
        $filename = implode(DIRECTORY_SEPARATOR, [$this->storageProviderDirectory, "1"]);

        // Set a Document mock.
        $document = new TestDocument();
        $document->setId(1);
        $document->setType(DocumentInterface::TYPE_DOCUMENT);
        $document->setUploadedFile($this->uploadedFile);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        $obj->uploadDocument($document);
        $this->assertFileExists($filename);
    }

    /**
     * Tests the uploadDocument() method.
     *
     * @return void
     */
    public function testUploadDocumentWithDirectory() {

        // Set a Document mock.
        $directory = new Document();
        $directory->setType(DocumentInterface::TYPE_DIRECTORY);

        $obj = new FilesystemStorageProvider($this->logger, $this->storageProviderDirectory);

        try {

            $obj->uploadDocument($directory);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
        }
    }
}
