<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Provider;

use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Provider\FilesystemStorageProvider;
use WBW\Bundle\EDMBundle\Tests\Fixtures\App\TestDocument;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;
use WBW\Library\Core\Utility\FileUtility;

/**
 * Filesystem storage provider test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider
 * @final
 */
final class FilesystemStorageProviderTest extends PHPUnit_Framework_TestCase {

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
     * Directoty.
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
     * Entity manager.
     *
     * @var ObjectManager
     */
    private $em;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {

        $this->directory = getcwd();

        $this->em = $this->getMockBuilder(ObjectManager::class)->getMock();

        $file = fopen($this->directory . "/Tests/Fixtures/App/TestDocument.bak.php", "w");
        fwrite($file, FileUtility::getContents($this->directory . "/Tests/Fixtures/App/TestDocument.php"));
        fclose($file);

        $this->dir1 = new TestDocument();
        $this->dir1->setId(1);
        $this->dir1->setName("phpunit");
        $this->dir1->setType(Document::TYPE_DIRECTORY);

        $this->dir2 = new TestDocument();
        $this->dir2->setId(2);
        $this->dir2->setName("unittest");
        $this->dir2->setType(Document::TYPE_DIRECTORY);

        $this->dir3 = new TestDocument();
        $this->dir3->setId(3);
        $this->dir3->setName("functionaltest");
        $this->dir3->setType(Document::TYPE_DIRECTORY);

        $this->doc1 = new TestDocument();
        $this->doc1->setId(4);
        $this->doc1->setName("class");
        $this->doc1->setType(Document::TYPE_DOCUMENT);
        $this->doc1->setUpload(new UploadedFile($this->directory . "/Tests/Fixtures/App/TestDocument.bak.php", "TestDocument.php", "application/x-php", 604, null, true));

        $this->dir1->addChildren($this->dir2);
        $this->dir2->addChildren($this->dir3);
        $this->dir2->addChildren($this->doc1);
    }

    /**
     * Tests the onNewDirectory() method.
     *
     * @return void
     */
    public function testOnNewDirectory() {

        $obj = new FilesystemStorageProvider($this->directory);

        try {
            $obj->onNewDirectory($this->doc1);
        } catch (Exception $ex) {
            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a directory", $ex->getMessage());
        }

        $obj->onNewDirectory($this->dir1);
        $this->assertFileExists($this->directory . "/1");

        $obj->onNewDirectory($this->dir2);
        $this->assertFileExists($this->directory . "/1/2");

        $obj->onNewDirectory($this->dir3);
        $this->assertFileExists($this->directory . "/1/2/3");
    }

    /**
     * Tests the onUploadedDocument() method.
     *
     * @return void
     * @depends testOnNewDirectory
     */
    public function testOnUploadedDocument() {

        $obj = new FilesystemStorageProvider($this->directory);

        try {
            $obj->onUploadedDocument($this->dir1);
        } catch (Exception $ex) {
            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a document", $ex->getMessage());
        }

        $obj->onUploadedDocument($this->doc1);
        $this->assertFileExists($this->directory . "/1/2/4");
    }

    /**
     * Tests the onMovedDocument() method.
     *
     * @return void
     * @depends testOnNewDirectory
     */
    public function testOnMovedDocument() {

        $obj = new FilesystemStorageProvider(getcwd());

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

        $obj = new FilesystemStorageProvider($this->directory);

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

        $obj = new FilesystemStorageProvider($this->directory);

        try {
            $obj->readDocument($this->dir1);
        } catch (Exception $ex) {
            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a document", $ex->getMessage());
        }

        $this->assertContains("<?php", $obj->readDocument($this->doc1));
    }

    /**
     * Tests the onDeletedDocument() method.
     *
     * @return void
     * @depends testOnMovedDocument
     */
    public function testOnDeletedDocument() {

        $obj = new FilesystemStorageProvider($this->directory);

        try {
            $obj->onDeletedDocument($this->dir1);
        } catch (Exception $ex) {
            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a document", $ex->getMessage());
        }

        $obj->onDeletedDocument($this->doc1);
        $this->assertFileNotExists($this->directory . "/1/2/4");
    }

    /**
     * Tests the onDeletedDirectory() method.
     *
     * @return void
     * @depends testOnMovedDocument
     */
    public function testOnDeletedDirectory() {

        $obj = new FilesystemStorageProvider($this->directory);

        $this->dir3->setParent($this->dir1); // This directory was moved.

        try {
            $obj->onDeletedDirectory($this->doc1);
        } catch (Exception $ex) {
            $this->assertInstanceOf(IllegalArgumentException::class, $ex);
            $this->assertEquals("The document must be a directory", $ex->getMessage());
        }

        $obj->onDeletedDirectory($this->dir3);
        $this->assertFileNotExists($this->directory . "/1/3");

        $obj->onDeletedDirectory($this->dir2);
        $this->assertFileNotExists($this->directory . "/1/2");

        $obj->onDeletedDirectory($this->dir1);
        $this->assertFileNotExists($this->directory . "/1");
    }

}
