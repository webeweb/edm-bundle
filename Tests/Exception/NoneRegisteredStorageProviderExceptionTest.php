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

use PHPUnit_Framework_TestCase;
use WBW\Bundle\EDMBundle\Exception\NoneRegisteredStorageProviderException;

/**
 * None registered storage provider exception test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Exception
 * @final
 */
final class NoneRegisteredStorageProviderExceptionTest extends PHPUnit_Framework_TestCase {

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstructor() {

        $obj = new NoneRegisteredStorageProviderException();

        $this->assertEquals("None registered storage provider", $obj->getMessage());
    }

}
