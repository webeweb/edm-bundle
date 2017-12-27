<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Entity;

use DateTime;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Entity\Document;

/**
 * DocumentTest.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Entity
 */
final class DocumentTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests __construct() method.
	 *
	 * @return void.
	 */
	public function testConstructor() {

		$obj = new Document();

		$this->assertEquals(null, $obj->getAlphabeticalTreeSortLabel());
		$this->assertEquals(null, $obj->getAlphabeticalTreeSortParent());
		$this->assertCount(0, $obj->getChildrens());
		$this->assertEquals(null, $obj->getChoiceLabel());
		$this->assertEquals(null, $obj->getCreatedAt());
		$this->assertEquals(null, $obj->getExtension());
		$this->assertEquals(null, $obj->getExtensionBackedUp());
		$this->assertEquals(null, $obj->getId());
		$this->assertEquals(null, $obj->getName());
		$this->assertEquals(null, $obj->getNameBackedUp());
		$this->assertEquals(null, $obj->getParent());
		$this->assertEquals(null, $obj->getParentBackedUp());
		$this->assertEquals(null, $obj->getSize());
		$this->assertEquals(Document::TYPE_DOCUMENT, $obj->getType());
		$this->assertEquals(null, $obj->getUpdatedAt());
		$this->assertEquals(null, $obj->getUpload());
	}

	/**
	 * Tests the addChildren() method.
	 *
	 * @return void
	 */
	public function testAddChildren() {

		$obj = new Document();
		$arg = new Document();

		$obj->addChildren($arg);
		$this->assertEquals($arg, $obj->getChildrens()[0]);
		$this->assertEquals(true, $obj->hasChildrens());
	}

	/**
	 * Tests the removeChildren() method.
	 *
	 * @return void
	 */
	public function testRemoveChildren() {

		$obj = new Document();
		$arg = new Document();

		$obj->addChildren($arg);
		$this->assertEquals($arg, $obj->getChildrens()[0]);

		$obj->removeChildren($arg);
		$this->assertCount(0, $obj->getChildrens());
	}

	/**
	 * Tests setCreatedAt() method.
	 *
	 * @return void
	 */
	public function testSetCreatedAt() {

		$obj = new Document();
		$arg = new DateTime();

		$obj->setCreatedAt($arg);
		$this->assertEquals($arg, $obj->getCreatedAt());
	}

	/**
	 * Tests setExtension() method.
	 *
	 * @return void
	 */
	public function testSetExtension() {

		$obj = new Document();

		$obj->setExtension("extension");
		$this->assertEquals("extension", $obj->getExtension());
	}

	/**
	 * Tests setExtensionBackedUp() method.
	 *
	 * @return void
	 */
	public function testSetExtensionBackedUp() {

		$obj = new Document();

		$obj->setExtensionBackedUp("extensionBackedUp");
		$this->assertEquals("extensionBackedUp", $obj->getExtensionBackedUp());
	}

	/**
	 * Tests setName() method.
	 *
	 * @return void
	 */
	public function testSetName() {

		$obj = new Document();

		$obj->setName("name");
		$this->assertEquals("name", $obj->getName());
		$this->assertEquals("name", $obj->getAlphabeticalTreeSortLabel());
		$this->assertEquals("name", $obj->getChoiceLabel());
	}

	/**
	 * Tests setNameBackedUp() method.
	 *
	 * @return void
	 */
	public function testSetNameBackedUp() {

		$obj = new Document();

		$obj->setNameBackedUp("nameBackedUp");
		$this->assertEquals("nameBackedUp", $obj->getNameBackedUp());
	}

	/**
	 * Tests setParent() method.
	 *
	 * @return void
	 */
	public function testSetParent() {

		$obj = new Document();
		$arg = new Document();

		$obj->setParent($arg);
		$this->assertEquals($arg, $obj->getParent());
		$this->assertEquals($arg, $obj->getAlphabeticalTreeSortParent());
	}

	/**
	 * Tests setParentBackedUp() method.
	 *
	 * @return void
	 */
	public function testSetParentBackedUp() {

		$obj = new Document();
		$arg = new Document();

		$obj->setParentBackedUp($arg);
		$this->assertEquals($arg, $obj->getParentBackedUp());
	}

	/**
	 * Tests setSize() method.
	 *
	 * @return void
	 */
	public function testSetSize() {

		$obj = new Document();

		$obj->setSize(0);
		$this->assertEquals(0, $obj->getSize());
	}

	/**
	 * Tests setType() method.
	 *
	 * @return void
	 */
	public function testSetType() {

		$obj = new Document();

		$obj->setType(-1);
		$this->assertEquals(null, $obj->getType());

		$obj->setType(Document::TYPE_DIRECTORY);
		$this->assertEquals(Document::TYPE_DIRECTORY, $obj->getType());

		$obj->setType(Document::TYPE_DOCUMENT);
		$this->assertEquals(Document::TYPE_DOCUMENT, $obj->getType());
	}

	/**
	 * Tests setUpdatedAt() method.
	 *
	 * @return void
	 */
	public function testSetUpdatedAt() {

		$obj = new Document();
		$arg = new DateTime();

		$obj->setUpdatedAt($arg);
		$this->assertEquals($arg, $obj->getUpdatedAt());
	}

	/**
	 * Tests the setUpload() method.
	 *
	 * @return void.
	 */
	public function testSetUplaod() {

		$obj = new Document();
		$arg = new UploadedFile(getcwd() . "/Tests/Entity/DocumentTest.php", "");

		$obj->setUpload($arg);
		$this->assertEquals($arg, $obj->getUpload());
	}

}
