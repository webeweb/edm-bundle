<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests;

use WBW\Bundle\CoreBundle\Provider\AssetsProviderInterface;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\WBWEDMBundle;

/**
 * EDM bundle test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests
 */
class WBWEDMBundleTest extends AbstractTestCase {

    /**
     * Tests the build() method.
     *
     * @return void
     */
    public function testBuild(): void {

        $obj = new WBWEDMBundle();

        $this->assertNull($obj->build($this->containerBuilder));
    }

    /**
     * Tests the getAssetsRelativeDirectory() method.
     *
     * @return void
     */
    public function testGetAssetsRelativeDirectory(): void {

        $obj = new WBWEDMBundle();

        $this->assertEquals(AssetsProviderInterface::ASSETS_RELATIVE_DIRECTORY, $obj->getAssetsRelativeDirectory());
    }

    /**
     * Tests the getContainerExtension() method.
     *
     * @return void
     */
    public function testGetContainerExtension(): void {

        $obj = new WBWEDMBundle();

        $this->assertInstanceOf(WBWEDMExtension::class, $obj->getContainerExtension());
    }
}
