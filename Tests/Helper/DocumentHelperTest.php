<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Helper;

use Exception;
use InvalidArgumentException;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\TestFixtures;

/**
 * Document helper test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Helper
 */
class DocumentHelperTest extends AbstractTestCase {

    /**
     * Tests the flattenChildren() method.
     *
     * @return void
     */
    public function testFlattenChildren() {

        $arg = TestFixtures::getDocuments();

        $res = DocumentHelper::flattenChildren($arg[0]);
        $this->assertCount(9, $res);

        $this->assertSame($arg[1], $res[0]);
        $this->assertSame($arg[2], $res[1]);
        $this->assertSame($arg[3], $res[2]);
        $this->assertSame($arg[4], $res[3]);
        $this->assertSame($arg[5], $res[4]);
        $this->assertSame($arg[6], $res[5]);
        $this->assertSame($arg[7], $res[6]);
        $this->assertSame($arg[8], $res[7]);
        $this->assertSame($arg[9], $res[8]);
    }

    /**
     * Tests the getFilename() method.
     *
     * @return void
     */
    public function testGetFilenameWithDirectory() {

        // Set a Document mock.
        $document = new Document();
        $document->setName("directory");
        $document->setType(Document::TYPE_DIRECTORY);

        $this->assertEquals("directory", DocumentHelper::getFilename($document));
    }

    /**
     * Tests the getFilename() method.
     *
     * @return void
     */
    public function testGetFilenameWithDocument() {

        // Set a Document mock.
        $document = new Document();
        $document->setName("filename");
        $document->setExtension("ext");
        $document->setType(Document::TYPE_DOCUMENT);

        $this->assertEquals("filename.ext", DocumentHelper::getFilename($document));
    }

    /**
     * Tests the getPathname() method.
     *
     * @return void
     */
    public function testGetPathnameWithDirectory() {

        // Set a Document mock.
        $document = new Document();
        $document->setName("directory");
        $document->setType(Document::TYPE_DIRECTORY);

        $this->assertEquals("directory", DocumentHelper::getPathname($document));
    }

    /**
     * Tests the getPathname() method.
     *
     * @return void
     */
    public function testGetPathnameWithDocument() {

        // Set a Document mock.
        $document = new Document();
        $document->setName("filename");
        $document->setExtension("ext");
        $document->setType(Document::TYPE_DOCUMENT);
        $document->setParent(new Document());
        $document->getParent()->setName("directory")->setType(Document::TYPE_DIRECTORY);

        $this->assertEquals("directory/filename.ext", DocumentHelper::getPathname($document));
    }

    /**
     * Tests the getPaths() method.
     *
     * @return void
     */
    public function testGetPaths() {

        // Set a Document mock.
        $document = new Document();
        $document->addChild(new Document());

        $this->assertEquals([$document], DocumentHelper::getPaths($document));
        $this->assertEquals([$document, $document->getChildren()[0]], DocumentHelper::getPaths($document->getChildren()[0]));
    }

    /**
     * Tests the isDirectory() method.
     *
     * @return void
     */
    public function testIsDirectory() {

        // Set a Document mock.
        $document = new Document();
        $document->setType(DocumentInterface::TYPE_DIRECTORY);

        $this->assertTrue(DocumentHelper::isDirectory($document));
    }

    /**
     * Tests the isDirectory() method.
     *
     * @return void
     */
    public function testIsDirectoryWithInvalidArgumentException() {

        // Set a Document mock.
        $document = new Document();
        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        try {

            DocumentHelper::isDirectory($document);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
            $this->assertEquals("The document must be of 'directory' type", $ex->getMessage());
        }
    }

    /**
     * Tests the isDocument() method.
     *
     * @return void
     */
    public function testIsDocument() {

        // Set a Document mock.
        $document = new Document();
        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        $this->assertTrue(DocumentHelper::isDocument($document));
    }

    /**
     * Tests the isDocument() method.
     *
     * @return void
     */
    public function testIsDocumentWithInvalidArgumentException() {

        // Set a Document mock.
        $document = new Document();
        $document->setType(DocumentInterface::TYPE_DIRECTORY);

        try {

            DocumentHelper::isDocument($document);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
            $this->assertEquals("The document must be of 'document' type", $ex->getMessage());
        }
    }

    /**
     * Tests the serialize() method.
     *
     * @return void
     */
    public function testSerialize() {

        // Set a Document mock.
        $document = TestFixtures::getDocuments()[1];

        $res = DocumentHelper::serialize($document);
        $this->assertArrayHasKey("id", $res);
        $this->assertArrayHasKey("children", $res);
        $this->assertArrayHasKey("createdAt", $res);
        $this->assertArrayHasKey("extension", $res);
        $this->assertArrayHasKey("filename", $res);
        $this->assertArrayHasKey("hashMd5", $res);
        $this->assertArrayHasKey("hashSha1", $res);
        $this->assertArrayHasKey("hashSha256", $res);
        $this->assertArrayHasKey("mimeType", $res);
        $this->assertArrayHasKey("name", $res);
        $this->assertArrayHasKey("numberDownloads", $res);
        $this->assertArrayHasKey("parent", $res);
        $this->assertArrayHasKey("size", $res);
        $this->assertArrayHasKey("type", $res);
        $this->assertArrayHasKey("updatedAt", $res);

        $this->assertCount(15, $res["parent"]);
    }
}
