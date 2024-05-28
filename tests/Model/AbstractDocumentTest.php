<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace WBW\Bundle\EDMBundle\Tests\Model;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use JsonSerializable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Model\TestDocument;
use WBW\Library\Common\Sorter\AlphabeticalNodeInterface;

/**
 * Abstract document test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Model
 */
class AbstractDocumentTest extends AbstractTestCase {

    /**
     * Test decreaseSize()
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
     * Test increaseSize()
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
     * Test incrementNumberDownloads()
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
     * Test isDirectory()
     *
     * @return void
     */
    public function testIsDirectory(): void {

        $obj = new TestDocument();

        $obj->setType(DocumentInterface::TYPE_DIRECTORY);
        $this->assertTrue($obj->isDirectory());
        $this->assertFalse($obj->isDocument());
    }

    /**
     * Test isDocument()
     *
     * @return void
     */
    public function testIsDocument(): void {

        $obj = new TestDocument();

        $obj->setType(DocumentInterface::TYPE_DOCUMENT);
        $this->assertFalse($obj->isDirectory());
        $this->assertTrue($obj->isDocument());
    }

    /**
     * Test jsonSerialize()
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
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
     * Test preRemove()
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testPreRemove(): void {

        $obj = new TestDocument();

        $obj->preRemove();
        $this->assertNull(null);
    }

    /**
     * Test preRemove()
     *
     * @return void
     */
    public function testPreRemoveWithForeignKeyConstraintViolationException(): void {

        $obj = new TestDocument();
        $obj->addChild(new TestDocument());

        try {
            $obj->preRemove();
        } catch (Throwable $ex) {

            $this->assertInstanceof(Throwable::class, $ex);
            $this->assertEquals("This directory is not empty", $ex->getMessage());
        }
    }

    /**
     * Test removeChildren()
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
     * Test saveParent() method.
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
     * Test setNumberDownloads()
     *
     * @return void
     */
    public function testSetNumberDownloads(): void {

        $obj = new TestDocument();

        $obj->setNumberDownloads(2018);
        $this->assertEquals(2018, $obj->getNumberDownloads());
    }

    /**
     * Test setParent() method.
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
     * Test setType() method.
     *
     * @return void
     */
    public function testSetType(): void {

        $obj = new TestDocument();

        $obj->setType(DocumentInterface::TYPE_DIRECTORY);
        $this->assertEquals(DocumentInterface::TYPE_DIRECTORY, $obj->getType());
    }

    /**
     * Test setType() method.
     *
     * @return void
     */
    public function testSetTypeWithInvalidArgumentException(): void {

        $obj = new TestDocument();

        try {
            $obj->setType(-1);
        } catch (Throwable $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
            $this->assertEquals('The type "-1" is invalid', $ex->getMessage());
        }
    }

    /**
     * Test setUploadedFile()
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
     * Test __construct() method.
     *
     * @return void
     */
    public function test__construct(): void {

        $obj = new TestDocument();

        $this->assertInstanceOf(JsonSerializable::class, $obj);
        $this->assertInstanceOf(DocumentInterface::class, $obj);
        $this->assertInstanceOf(AlphabeticalNodeInterface::class, $obj);

        $this->assertNull($obj->getId());
        $this->assertNotNull($obj->getCreatedAt());
        $this->assertNull($obj->getExtension());
        $this->assertNull($obj->getHashMd5());
        $this->assertNull($obj->getHashSha1());
        $this->assertNull($obj->getHashSha256());
        $this->assertNull($obj->getMimeType());
        $this->assertNull($obj->getName());
        $this->assertEquals(0, $obj->getSize());
        $this->assertEquals(DocumentInterface::TYPE_DOCUMENT, $obj->getType());
        $this->assertNull($obj->getUid());
        $this->assertNull($obj->getUpdatedAt());

        $this->assertCount(0, $obj->getChildren());
        $this->assertEquals(0, $obj->getNumberDownloads());
        $this->assertNull($obj->getParent());
        $this->assertNull($obj->getSavedParent());
        $this->assertNull($obj->getUploadedFile());

        $this->assertFalse($obj->hasChildren());

        $this->assertNull($obj->getAlphabeticalNodeLabel());
        $this->assertNull($obj->getAlphabeticalNodeParent());
    }
}
