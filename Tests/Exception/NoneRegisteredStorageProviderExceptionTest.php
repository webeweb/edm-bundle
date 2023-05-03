<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Exception;

use WBW\Bundle\EDMBundle\Exception\NoneRegisteredStorageProviderException;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * None registered storage provider exception test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Exception
 */
class NoneRegisteredStorageProviderExceptionTest extends AbstractTestCase {

    /**
     * Test __construct()
     *
     * @return void
     */
    public function test__construct(): void {

        $obj = new NoneRegisteredStorageProviderException();

        $this->assertEquals("None registered storage provider", $obj->getMessage());
    }
}
