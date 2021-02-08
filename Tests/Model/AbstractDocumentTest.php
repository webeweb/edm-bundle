<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Model;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Model\TestDocument;

/**
 * Abstract document test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Model
 */
class AbstractDocumentTest extends AbstractTestCase {

    /**
     * Tests the decreaseSize() method.
     *
     * @return void
     */
    public function testDecreaseSize(): void {

        $obj = new TestDocument();
        $obj->setSize(1);

        $obj->decreaseSize(1);
        $this->assertEquals(0, $obj->getSize());
    }

    /**
     * Tests the increaseSize() method.
     *
     * @return void
     */
    public function testIncreaseSize(): void {

        $obj = new TestDocument();
        $obj->setSize(1);

        $obj->increaseSize(1);
        $this->assertEquals(2, $obj->getSize());
    }

    /**
     * Tests the incrementNumberDownloads() method.
     *
     * @return void
     */
    public function testIncrementNumberDownloads(): void {

        $obj = new TestDocument();
        $obj->setNumberDownloads(1);

        $obj->incrementNumberDownloads();
        $this->assertEquals(2, $obj->getNumberDownloads());
    }

    /**
     * Tests the isDirectory() method.
     *
     * @return void
     */
    public function testIsDirectory(): void {

        $obj = new TestDocument();

        $obj->setType(Document::TYPE_DIRECTORY);
        $this->assertTrue($obj->isDirectory());
        $this->assertFalse($obj->isDocument());
    }

    /**
     * Tests the isDocument() method.
     *
     * @return void
     */
    public function testIsDocument(): void {

        $obj = new TestDocument();

        $obj->setType(Document::TYPE_DOCUMENT);
        $this->assertFalse($obj->isDirectory());
        $this->assertTrue($obj->isDocument());
    }

    /**
     * Tests the jsonSerialize() method.
     *
     * @return void
     */
    public function testJsonSerialize(): void {

        $obj = new TestDocument();

        $this->assertTrue(is_array($obj->jsonSerialize()));
    }

    /**
     * Tests the preRemove() method.
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testPreRemove(): void {

        $obj = new TestDocument();

        $this->assertNull($obj->preRemove());
    }

    /**
     * Tests the preRemove() method.
     *
     * @return void
     */
    public function testPreRemoveWithForeignKeyConstraintViolationException(): void {

        $obj = new TestDocument();
        $obj->addChild(new TestDocument());

        try {

            $obj->preRemove();
        } catch (Exception $ex) {

            $this->assertInstanceof(ForeignKeyConstraintViolationException::class, $ex);
            $this->assertEquals("This directory is not empty", $ex->getMessage());
        }
    }

    /**
     * Tests the removeChildren() method.
     *
     * @return void
     */
    public function testRemoveChild(): void {

        // Set a Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();

        $obj = new TestDocument();

        $obj->addChild($document);
        $this->assertSame($document, $obj->getChildren()[0]);

        $obj->removeChild($document);
        $this->assertCount(0, $obj->getChildren());
    }

    /**
     * Tests saveParent() method.
     *
     * @return void
     */
    public function testSaveParent(): void {

        // Set a Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();

        $obj = new TestDocument();
        $obj->setParent($document);

        $obj->saveParent();
        $this->assertSame($document, $obj->getSavedParent());
    }

    /**
     * Tests the setNumberDownloads() method.
     *
     * @return void
     */
    public function testSetNumberDownloads(): void {

        $obj = new TestDocument();

        $obj->setNumberDownloads(2018);
        $this->assertEquals(2018, $obj->getNumberDownloads());
    }

    /**
     * Tests setParent() method.
     *
     * @return void
     */
    public function testSetParent(): void {

        // Set a Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();

        $obj = new TestDocument();

        $obj->setParent($document);
        $this->assertSame($document, $obj->getParent());
    }

    /**
     * Tests setType() method.
     *
     * @return void
     */
    public function testSetType(): void {

        $obj = new TestDocument();

        $obj->setType(Document::TYPE_DIRECTORY);
        $this->assertEquals(Document::TYPE_DIRECTORY, $obj->getType());
    }

    /**
     * Tests setType() method.
     *
     * @return void
     */
    public function testSetTypeWithInvalidArgumentException(): void {

        $obj = new TestDocument();

        try {

            $obj->setType(-1);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
            $this->assertEquals('The type "-1" is invalid', $ex->getMessage());
        }
    }

    /**
     * Tests the setUploadedFile() method.
     *
     * @return void
     */
    public function testSetUploadedFile(): void {

        // Set an Uploaded file mock.
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();

        $obj = new TestDocument();

        $obj->setUploadedFile($uploadedFile);
        $this->assertEquals($uploadedFile, $obj->getUploadedFile());
    }

    /**
     * Tests __construct() method.
     *
     * @return void
     */
    public function test__construct(): void {

        $obj = new TestDocument();

        $this->assertNull($obj->getAlphabeticalTreeNodeLabel());
        $this->assertNull($obj->getAlphabeticalTreeNodeParent());
        $this->assertCount(0, $obj->getChildren());
        $this->assertNotNull($obj->getCreatedAt());
        $this->assertNull($obj->getExtension());
        $this->assertNull($obj->getHashMd5());
        $this->assertNull($obj->getHashSha1());
        $this->assertNull($obj->getHashSha256());
        $this->assertNull($obj->getId());
        $this->assertNull($obj->getMimeType());
        $this->assertNull($obj->getName());
        $this->assertEquals(0, $obj->getNumberDownloads());
        $this->assertNull($obj->getParent());
        $this->assertNull($obj->getSavedParent());
        $this->assertEquals(0, $obj->getSize());
        $this->assertEquals(Document::TYPE_DOCUMENT, $obj->getType());
        $this->assertNull($obj->getUpdatedAt());
        $this->assertNull($obj->getUploadedFile());
        $this->assertFalse($obj->hasChildren());
    }
}
