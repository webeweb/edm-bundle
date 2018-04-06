<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests;

use WBW\Bundle\BootstrapBundle\Tests\AbstractBootstrapTest;
use WBW\Bundle\EDMBundle\EDMBundle;

/**
 * EDM bundle test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests
 * @final
 */
final class EDMBundleTest extends AbstractBootstrapTest {

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        parent::setUp();
    }

    /**
     * Tests the build() method.
     *
     * @return void
     */
    public function testBuild() {

        $obj = new EDMBundle();

        $obj->build($this->containerBuilder);
    }

//put your code here
}
