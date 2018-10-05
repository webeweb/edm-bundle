<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Exception;

use WBW\Bundle\EDMBundle\Exception\NoneRegisteredStorageProviderException;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;

/**
 * None registered storage provider exception test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Exception
 * @final
 */
final class NoneRegisteredStorageProviderExceptionTest extends AbstractFrameworkTestCase {

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $obj = new NoneRegisteredStorageProviderException();

        $res = "None registered storage provider";
        $this->assertEquals($res, $obj->getMessage());
    }

}
