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

use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Exception;
use InvalidArgumentException;
use JsonSerializable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Model\TestDocument;
use WBW\Library\Sorter\Model\AlphabeticalTreeNodeInterface;

/**
 * Abstract document test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Model
 */
class AbstractDocumentTest extends AbstractTestCase {

    /**
     * Tests decreaseSize()
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
     * Tests increaseSize()
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
     * Tests incrementNumberDownloads()
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
     * Tests isDirectory()
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
     * Tests isDocument()
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
     * Tests jsonSerialize()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testJsonSerialize(): void {

        // Set the expected data.
        $data = file_get_contents(__DIR__ . "/AbstractDocumentTest.testJsonSerialize.json");

        // Set the date/time mocks.
        $createdAt = new DateTime("2021-10-29 11:45:00.00000", new DateTimeZone("UTC"));
        $updatedAt = new DateTime("2021-10-29 12:00:00.00000", new DateTimeZone("UTC"));

        // Set a child mock.
        $child = new TestDocument();
        $child->setId(2);
        $child->setCreatedAt(null);

        // Set a parent mock.
        $parent = new TestDocument();
        $parent->setCreatedAt(null);

        $obj = new TestDocument();
        $obj->setId(1);
        $obj->setCreatedAt($createdAt);
        $obj->setExtension("ext");
        $obj->setHashMd5("hashMd5");
        $obj->setHashSha1("hashSha1");
        $obj->setHashSha256("hashSha256");
        $obj->setMimeType("mimeType");
        $obj->setName("name");
        $obj->setNumberDownloads(438);
        $obj->setParent($parent);
        $obj->setSize(4);
        $obj->setType(DocumentInterface::TYPE_DOCUMENT);
        $obj->setUid("uid");
        $obj->setUpdatedAt($updatedAt);

        $obj->addChild($child);

        $res = $obj->jsonSerialize();
        $this->assertCount(16, $res);

        $this->assertEquals($data, json_encode($res, JSON_PRETTY_PRINT) . "\n");
    }

    /**
     * Tests preRemove()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testPreRemove(): void {

        $obj = new TestDocument();

        $this->assertNull($obj->preRemove());
    }

    /**
     * Tests preRemove()
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
     * Tests removeChildren()
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
     * Tests setNumberDownloads()
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
     * Tests setUploadedFile()
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

        $this->assertInstanceOf(JsonSerializable::class, $obj);
        $this->assertInstanceOf(DocumentInterface::class, $obj);
        $this->assertInstanceOf(AlphabeticalTreeNodeInterface::class, $obj);

        $this->assertNull($obj->getId());
        $this->assertNotNull($obj->getCreatedAt());
        $this->assertNull($obj->getExtension());
        $this->assertNull($obj->getHashMd5());
        $this->assertNull($obj->getHashSha1());
        $this->assertNull($obj->getHashSha256());
        $this->assertNull($obj->getMimeType());
        $this->assertNull($obj->getName());
        $this->assertEquals(0, $obj->getSize());
        $this->assertEquals(Document::TYPE_DOCUMENT, $obj->getType());
        $this->assertNull($obj->getUid());
        $this->assertNull($obj->getUpdatedAt());

        $this->assertCount(0, $obj->getChildren());
        $this->assertEquals(0, $obj->getNumberDownloads());
        $this->assertNull($obj->getParent());
        $this->assertNull($obj->getSavedParent());
        $this->assertNull($obj->getUploadedFile());

        $this->assertFalse($obj->hasChildren());

        $this->assertNull($obj->getAlphabeticalTreeNodeLabel());
        $this->assertNull($obj->getAlphabeticalTreeNodeParent());
    }
}
