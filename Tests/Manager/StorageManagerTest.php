<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Event\DocumentEvents;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Entity\TestDocument;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;
use WBW\Library\Core\Utility\FileUtility;

/**
 * Storage manager test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Manager
 * @final
 */
final class StorageManagerTest extends PHPUnit_Framework_TestCase {

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

		$file = fopen($this->directory . "/Tests/Fixtures/Entity/TestDocument.bak.php", "w");
		fwrite($file, FileUtility::getContents($this->directory . "/Tests/Fixtures/Entity/TestDocument.php"));
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
		$this->doc1->setUpload(new UploadedFile($this->directory . "/Tests/Fixtures/Entity/TestDocument.bak.php", "TestDocument.php", "application/x-php", 604, null, true));

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

		$obj = new StorageManager($this->em, $this->directory);

		try {
			$obj->onNewDirectory(new DocumentEvent(DocumentEvents::DIRECTORY_NEW, $this->doc1));
		} catch (Exception $ex) {
			$this->assertInstanceOf(IllegalArgumentException::class, $ex);
			$this->assertEquals("The document must be a directory", $ex->getMessage());
		}

		$obj->onNewDirectory(new DocumentEvent(DocumentEvents::DIRECTORY_NEW, $this->dir1));
		$this->assertFileExists($this->directory . "/1");

		$obj->onNewDirectory(new DocumentEvent(DocumentEvents::DIRECTORY_NEW, $this->dir2));
		$this->assertFileExists($this->directory . "/1/2");

		$obj->onNewDirectory(new DocumentEvent(DocumentEvents::DIRECTORY_NEW, $this->dir3));
		$this->assertFileExists($this->directory . "/1/2/3");
	}

	/**
	 * Tests the onUploadedDocument() method.
	 *
	 * @return void
	 * @depends testOnNewDirectory
	 */
	public function testOnUploadedDocument() {

		$obj = new StorageManager($this->em, $this->directory);

		try {
			$obj->onUploadedDocument(new DocumentEvent(DocumentEvents::DOCUMENT_UPLOAD, $this->dir1));
		} catch (Exception $ex) {
			$this->assertInstanceOf(IllegalArgumentException::class, $ex);
			$this->assertEquals("The document must be a document", $ex->getMessage());
		}

		$obj->onUploadedDocument(new DocumentEvent(DocumentEvents::DOCUMENT_UPLOAD, $this->doc1));
		$this->assertFileExists($this->directory . "/1/2/4");
	}

	/**
	 * Tests the onMovedDocument() method.
	 *
	 * @return void
	 * @depends testOnNewDirectory
	 */
	public function testOnMovedDocument() {

		$obj = new StorageManager($this->em, getcwd());

		$this->dir3->setParentBackedUp($this->dir3->getParent());
		$this->dir2->removeChildren($this->dir3);
		$this->dir1->addChildren($this->dir3);

		$obj->onMovedDocument(new DocumentEvent(DocumentEvents::DIRECTORY_MOVE, $this->dir3));
		$this->assertFileExists($this->directory . "/1/3");
	}

	/**
	 * Tests the downloadDocument() method.
	 *
	 * @return void
	 * @depends testOnNewDirectory
	 */
	public function testDownloadDocument() {

		$obj = new StorageManager($this->em, $this->directory);

		$res = $obj->downloadDocument($this->dir1);
		$this->assertNotNull($res->getId());
		$this->assertEquals("zip", $res->getExtension());
		$this->assertEquals("application/zip", $res->getMimeType());
		$this->assertContains("phpunit-", $res->getName());
		$this->assertEquals(Document::TYPE_DOCUMENT, $res->getType());
	}

	/**
	 * Tests the onDeletedDocument() method.
	 *
	 * @return void
	 * @depends testOnMovedDocument
	 */
	public function testOnDeletedDocument() {

		$obj = new StorageManager($this->em, $this->directory);

		try {
			$obj->onDeletedDocument(new DocumentEvent(DocumentEvents::DOCUMENT_DELETE, $this->dir1));
		} catch (Exception $ex) {
			$this->assertInstanceOf(IllegalArgumentException::class, $ex);
			$this->assertEquals("The document must be a document", $ex->getMessage());
		}

		$obj->onDeletedDocument(new DocumentEvent(DocumentEvents::DOCUMENT_DELETE, $this->doc1));
		$this->assertFileNotExists($this->directory . "/1/2/4");
	}

	/**
	 * Tests the onDeletedDirectory() method.
	 *
	 * @return void
	 * @depends testOnMovedDocument
	 */
	public function testOnDeletedDirectory() {

		$obj = new StorageManager($this->em, $this->directory);

		$this->dir3->setParent($this->dir1); // This directory was moved.

		try {
			$obj->onDeletedDirectory(new DocumentEvent(DocumentEvents::DIRECTORY_DELETE, $this->doc1));
		} catch (Exception $ex) {
			$this->assertInstanceOf(IllegalArgumentException::class, $ex);
			$this->assertEquals("The document must be a directory", $ex->getMessage());
		}

		$obj->onDeletedDirectory(new DocumentEvent(DocumentEvents::DIRECTORY_DELETE, $this->dir3));
		$this->assertFileNotExists($this->directory . "/1/3");

		$obj->onDeletedDirectory(new DocumentEvent(DocumentEvents::DIRECTORY_DELETE, $this->dir2));
		$this->assertFileNotExists($this->directory . "/1/2");

		$obj->onDeletedDirectory(new DocumentEvent(DocumentEvents::DIRECTORY_DELETE, $this->dir1));
		$this->assertFileNotExists($this->directory . "/1");
	}

}
