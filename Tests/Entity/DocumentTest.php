<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Entity;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Document test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Entity
 */
class DocumentTest extends AbstractTestCase {

    /**
     * Tests __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $obj = new Document();

        $this->assertNull($obj->getAlphabeticalTreeNodeLabel());
        $this->assertNull($obj->getAlphabeticalTreeNodeParent());
        $this->assertCount(0, $obj->getChildren());
        $this->assertNull($obj->getChoiceLabel());
        $this->assertNotNull($obj->getCreatedAt());
        $this->assertNull($obj->getExtension());
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

    /**
     * Tests the decreaseSize() method.
     *
     * @return void
     */
    public function testDecreaseSize() {

        $obj = new Document();
        $obj->setSize(1);

        $obj->decreaseSize(1);
        $this->assertEquals(0, $obj->getSize());
    }

    /**
     * Tests the increaseSize() method.
     *
     * @return void
     */
    public function testIncreaseSize() {

        $obj = new Document();
        $obj->setSize(1);

        $obj->increaseSize(1);
        $this->assertEquals(2, $obj->getSize());
    }

    /**
     * Tests the incrementNumberDownloads() method.
     *
     * @return void
     */
    public function testIncrementNumberDownloads() {

        $obj = new Document();
        $obj->setNumberDownloads(1);

        $obj->incrementNumberDownloads();
        $this->assertEquals(2, $obj->getNumberDownloads());
    }

    /**
     * Tests the isDirectory() method.
     *
     * @return void
     */
    public function testIsDirectory() {

        $obj = new Document();

        $obj->setType(Document::TYPE_DIRECTORY);
        $this->assertTrue($obj->isDirectory());
        $this->assertFalse($obj->isDocument());
    }

    /**
     * Tests the isDocument() method.
     *
     * @return void
     */
    public function testIsDocument() {

        $obj = new Document();

        $obj->setType(Document::TYPE_DOCUMENT);
        $this->assertFalse($obj->isDirectory());
        $this->assertTrue($obj->isDocument());
    }

    /**
     * Tests the preRemove() method.
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testPreRemove() {

        $obj = new Document();

        $this->assertNull($obj->preRemove());
    }

    /**
     * Tests the preRemove() method.
     *
     * @return void
     */
    public function testPreRemoveWithForeignKeyConstraintViolationException() {

        $obj = new Document();
        $obj->addChild(new Document());

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
    public function testRemoveChild() {

        // Set a Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();

        $obj = new Document();

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
    public function testSaveParent() {

        // Set a Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();

        $obj = new Document();
        $obj->setParent($document);

        $obj->saveParent();
        $this->assertSame($document, $obj->getSavedParent());
    }

    /**
     * Tests the setNumberDownloads() method.
     *
     * @return void
     */
    public function testSetNumberDownloads() {

        $obj = new Document();

        $obj->setNumberDownloads(2018);
        $this->assertEquals(2018, $obj->getNumberDownloads());
    }

    /**
     * Tests setParent() method.
     *
     * @return void
     */
    public function testSetParent() {

        // Set a Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();

        $obj = new Document();

        $obj->setParent($document);
        $this->assertSame($document, $obj->getParent());
    }

    /**
     * Tests setType() method.
     *
     * @return void
     */
    public function testSetType() {

        $obj = new Document();

        $obj->setType(Document::TYPE_DIRECTORY);
        $this->assertEquals(Document::TYPE_DIRECTORY, $obj->getType());
    }

    /**
     * Tests setType() method.
     *
     * @return void
     */
    public function testSetTypeWithInvalidArgumentException() {

        $obj = new Document();

        try {

            $obj->setType(-1);
        } catch (Exception $ex) {

            $this->assertInstanceOf(InvalidArgumentException::class, $ex);
            $this->assertEquals("The type \"-1\" is invalid", $ex->getMessage());
        }
    }

    /**
     * Tests the setUploadedFile() method.
     *
     * @return void
     */
    public function testSetUploadedFile() {

        // Set an Uploaded file mock.
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();

        $obj = new Document();

        $obj->setUploadedFile($uploadedFile);
        $this->assertEquals($uploadedFile, $obj->getUploadedFile());
    }
}
