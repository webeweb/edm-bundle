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
     * Tests the setStorageManager() method.
     *
     * @return void
     */
    public function testSetStorageManager(): void {

        $obj = new TestStorageManagerTrait();

        $obj->setStorageManager($this->storageManager);
        $this->assertSame($this->storageManager, $obj->getStorageManager());
    }
}
