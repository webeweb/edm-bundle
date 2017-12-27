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
use WBW\Bundle\EDMBundle\Manager\StorageManager;

/**
 * Storage manager test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Manager
 * @final
 */
final class StorageManagerTest extends PHPUnit_Framework_TestCase {

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
	protected function setUp() {

		$this->directory = new Document();
		$this->directory->setName("phpunit");

		$this->subdirectory = new Document();
		$this->subdirectory->setName("unittest");
		$this->subdirectory->setParent($this->directory);
	}

	/**
	 * Tests the makeDirectory() method.
	 *
	 * @return void
	 */
	public function testMakeDirectory() {

		$obj = new StorageManager(getcwd());

		$this->assertEquals(true, $obj->makeDirectory($this->directory));
		$this->assertEquals(false, $obj->makeDirectory($this->directory));
		$this->assertEquals(true, $obj->makeDirectory($this->subdirectory));
	}

	/**
	 * Tests the renameDirectory() method.
	 *
	 * @return void
	 * @depends testMakeDirectory
	 */
	public function testRenameDirectory() {

		$obj = new StorageManager(getcwd());

		$this->subdirectory->backup();
		$this->subdirectory->setName("unit-test");
		$this->assertEquals(true, $obj->renameDirectory($this->subdirectory));
	}

	/**
	 * Tests the removeDirectory() method.
	 *
	 * @return void
	 * @depends testRenameDirectory
	 */
	public function testRemoveDirectory() {

		$obj = new StorageManager(getcwd());

		$this->subdirectory->setName("unit-test");
		$this->assertEquals(false, $obj->removeDirectory($this->directory));
		$this->assertEquals(true, $obj->removeDirectory($this->subdirectory));
		$this->assertEquals(true, $obj->removeDirectory($this->directory));
	}

}
