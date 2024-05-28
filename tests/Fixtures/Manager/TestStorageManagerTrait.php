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

namespace WBW\Bundle\EDMBundle\Tests\Fixtures\Manager;

use WBW\Bundle\EDMBundle\Manager\StorageManagerTrait;

/**
 * Test storage manager.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Fixtures\Manager
 */
class TestStorageManagerTrait {

    use StorageManagerTrait {
        setStorageManager as public;
    }
}
