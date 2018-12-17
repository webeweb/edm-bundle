<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Provider;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Provider\FileSystemStorageProvider;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Entity\TestDocument;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;
use WBW\Library\Core\FileSystem\FileHelper;

/**
 * File system storage provider test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider
 */
class FileSystemStorageProviderTest extends AbstractFrameworkTestCase {

    /**
     * Directory 1.
     *
     * @var Document
     */
    private $dir1;

    /**
     * Directory 2.
     *
     * @var Document
     */
    private $dir2;

    /**
     * Directory 3.
     *
     * @var Document
     */
    private $dir3;

    /**
     * Directory.
     *
     * @var string
     */
    private $directory;

    /**
     * Document 1.
     *
     * @var Document
     */
    private $doc1;

    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set a Directory mock.
        $this->directory = getcwd();

        // Set a Document mock.
        $file = fopen($this->directory . "/Tests/Fixtures/Entity/TestDocument.bak.php", "w");
        fwrite($file, FileHelper::getContents($this->directory . "/Tests/Fixtures/Entity/TestDocument.php"));
        fclose($file);

        // Set a Document mock.
        $this->dir1 = new TestDocument();
        $this->dir1->setId(1);
        $this->dir1->setName("phpunit");
        $this->dir1->setType(Document::TYPE_DIRECTORY);

        // Set a Document mock.
        $this->dir2 = new TestDocument();
        $this->dir2->setId(2);
        $this->dir2->setName("unittest");
        $this->dir2->setType(Document::TYPE_DIRECTORY);

        // Set a Document mock.
        $this->dir3 = new TestDocument();
        $this->dir3->setId(3);
        $this->dir3->setName("functionaltest");
        $this->dir3->setType(Document::TYPE_DIRECTORY);

        // Set a Document mock.
        $this->doc1 = new TestDocument();
        $this->doc1->setId(4);
        $this->doc1->setName("class");
        $this->doc1->setType(Document::TYPE_DOCUMENT);
        $this->doc1->setUpload(new UploadedFile($this->directory . "/Tests/Fixtures/Entity/TestDocument.bak.php", "TestDocument.php", "application/x-php", 604, null, true));

        $this->dir1->addChildren($this->dir2);
        $this->dir2->addChildren($this->dir3);
        $this->dir2->addChildren($this->doc1);

        // Set a Logger mock.
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        $this->assertEquals($this->directory, $obj->getDirectory());
        $this->assertSame($this->logger, $obj->getLogger());
    }

    /**
     * Tests the onNewDirectory() method.
     *
     * @return void
     */
    public function testOnNewDirectory() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        $obj->onNewDirectory($this->dir1);
        $this->assertFileExists($this->directory . "/1");

        $obj->onNewDirectory($this->dir2);
        $this->assertFileExists($this->directory . "/1/2");

        $obj->onNewDirectory($this->dir3);
        $this->assertFileExists($this->directory . "/1/2/3");
    }

    /**
     * Tests the onNewDirectory() method.
     *
     * @return void
     */
    public function testOnNewDirectoryWithIllegalArgumentException() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        try {

            $obj->onNewDirectory($this->doc1);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a directory", $ex->getMessage());
        }
    }

    /**
     * Tests the onUploadedDocument() method.
     *
     * @return void
     * @depends testOnNewDirectory
     */
    public function testOnUploadedDocument() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        $obj->onUploadedDocument($this->doc1);
        $this->assertFileExists($this->directory . "/1/2/4");
    }

    /**
     * Tests the onUploadedDocument() method.
     *
     * @return void
     */
    public function testOnUploadedDocumentWithIllegalArgumentException() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        try {

            $obj->onUploadedDocument($this->dir1);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a document", $ex->getMessage());
        }
    }

    /**
     * Tests the onMovedDocument() method.
     *
     * @return void
     * @depends testOnNewDirectory
     */
    public function testOnMovedDocument() {

        $obj = new FileSystemStorageProvider($this->logger, getcwd());

        $this->dir3->setParentBackedUp($this->dir3->getParent());
        $this->dir2->removeChildren($this->dir3);
        $this->dir1->addChildren($this->dir3);

        $obj->onMovedDocument($this->dir3);
        $this->assertFileExists($this->directory . "/1/3");
    }

    /**
     * Tests the downloadDocument() method.
     *
     * @return void
     * @depends testOnUploadedDocument
     */
    public function testDownloadDocument() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        $this->assertEquals($this->doc1, $obj->downloadDocument($this->doc1));

        $res = $obj->downloadDocument($this->dir1);
        $this->assertNotNull($res->getId());
        $this->assertEquals("zip", $res->getExtension());
        $this->assertEquals("application/zip", $res->getMimeType());
        $this->assertContains("phpunit-", $res->getName());
        $this->assertEquals(Document::TYPE_DOCUMENT, $res->getType());
    }

    /**
     * Tests the readDocument() method.
     *
     * @return void
     * @depends testOnUploadedDocument
     */
    public function testReadDocument() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        $this->assertContains("<?php", $obj->readDocument($this->doc1));
    }

    /**
     * Tests the readDocument() method.
     *
     * @return void
     */
    public function testReadDocumentWithIllegalArgumentException() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        try {

            $obj->readDocument($this->dir1);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a document", $ex->getMessage());
        }
    }

    /**
     * Tests the onDeletedDocument() method.
     *
     * @return void
     * @depends testOnMovedDocument
     */
    public function testOnDeletedDocument() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        $obj->onDeletedDocument($this->doc1);
        $this->assertFileNotExists($this->directory . "/1/2/4");
    }

    /**
     * Tests the onDeletedDocument() method.
     *
     * @return void
     */
    public function testOnDeletedDocumentWithIllegalArgumentException() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        try {

            $obj->onDeletedDocument($this->dir1);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a document", $ex->getMessage());
        }
    }

    /**
     * Tests the onDeletedDirectory() method.
     *
     * @return void
     * @depends testOnMovedDocument
     */
    public function testOnDeletedDirectory() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        $this->dir3->setParent($this->dir1); // This directory was moved.

        $obj->onDeletedDirectory($this->dir3);
        $this->assertFileNotExists($this->directory . "/1/3");

        $obj->onDeletedDirectory($this->dir2);
        $this->assertFileNotExists($this->directory . "/1/2");

        $obj->onDeletedDirectory($this->dir1);
        $this->assertFileNotExists($this->directory . "/1");
    }

    /**
     * Tests the onDeletedDirectory() method.
     *
     * @return void
     */
    public function testOnDeletedDirectoryWithIllegalArgumentException() {

        $obj = new FileSystemStorageProvider($this->logger, $this->directory);

        try {

            $obj->onDeletedDirectory($this->doc1);
        } catch (Exception $ex) {

            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a directory", $ex->getMessage());
        }
    }

}
