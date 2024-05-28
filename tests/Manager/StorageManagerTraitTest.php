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

namespace WBW\Bundle\EDMBundle\Tests\Manager;

use WBW\Bundle\EDMBundle\Manager\StorageManagerInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Manager\TestStorageManagerTrait;

/**
 * Storage manager trait test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Manager
 */
class StorageManagerTraitTest extends AbstractTestCase {

    /**
     * Test setStorageManager()
     *
     * @return void
     */
    public function testSetStorageManager(): void {

        // Set a Storage manager mock.
        $storageManager = $this->getMockBuilder(StorageManagerInterface::class)->getMock();

        $obj = new TestStorageManagerTrait();

        $obj->setStorageManager($storageManager);
        $this->assertSame($storageManager, $obj->getStorageManager());
    }
}
