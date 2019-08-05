<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Manager;

use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Manager\TestStorageManagerTrait;

/**
 * Storage manager trait test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Manager
 */
class StorageManagerTraitTest extends AbstractTestCase {

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $obj = new TestStorageManagerTrait();

        $this->assertNull($obj->getStorageManager());
    }

    /**
     * Tests the setStorageManager() method.
     *
     * @return void
     */
    public function testSetStorageManager() {

        // Set a Storage manager mock.
        $storageManager = $this->getMockBuilder(StorageManager::class)->disableOriginalConstructor()->getMock();

        $obj = new TestStorageManagerTrait();

        $obj->setStorageManager($storageManager);
        $this->assertSame($storageManager, $obj->getStorageManager());
    }
}
