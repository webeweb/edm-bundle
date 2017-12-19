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

use PHPUnit_Framework_TestCase;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Manager\DocumentManager;

/**
 * Document manager test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Manager
 * @final
 */
final class DocumentManagerTest extends PHPUnit_Framework_TestCase {

	/**
	 * Directory.
	 *
	 * @var Document
	 */
	private $directory;

	/**
	 * Subdirectory.
	 *
	 * @var Document
	 */
	private $subdirectory;

	/**
	 * {@inheritdoc}
	 */
	public function setUp() {

		$this->directory = new Document();
		$this->directory->setName("phpunit");

		$this->subdirectory = new Document();
		$this->subdirectory->setName("unittest");
		$this->subdirectory->setParent($this->directory);
	}

	/**
	 * Tests the mkdir() method.
	 *
	 * @return void
	 */
	public function testMkdir() {

		$obj = new DocumentManager(getcwd());

		$this->assertEquals(true, $obj->mkdir($this->directory));
		$this->assertEquals(false, $obj->mkdir($this->directory));
		$this->assertEquals(true, $obj->mkdir($this->subdirectory));
	}

	/**
	 * Tests the rename() method.
	 *
	 * @return void
	 * @depends testMkdir
	 */
	public function testRename() {

		$obj = new DocumentManager(getcwd());

		$this->subdirectory->backup();
		$this->subdirectory->setName("unit-test");
		$this->assertEquals(true, $obj->rename($this->subdirectory));
	}

	/**
	 * Tests the rmdir() method.
	 *
	 * @return void
	 * @depends testRename
	 */
	public function testRmdir() {

		$obj = new DocumentManager(getcwd());

		$this->subdirectory->setName("unit-test");
		$this->assertEquals(false, $obj->rmdir($this->directory));
		$this->assertEquals(true, $obj->rmdir($this->subdirectory));
		$this->assertEquals(true, $obj->rmdir($this->directory));
	}

}
