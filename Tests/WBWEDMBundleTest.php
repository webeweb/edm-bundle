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

use Exception;
use WBW\Bundle\CoreBundle\Provider\AssetsProviderInterface;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\WBWEDMBundle;
use WBW\Library\Symfony\Helper\AssetsHelper;

/**
 * EDM bundle test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests
 */
class WBWEDMBundleTest extends AbstractTestCase {

    /**
     * Tests build()
     *
     * @return void
     */
    public function testBuild(): void {

        $obj = new WBWEDMBundle();

        $this->assertNull($obj->build($this->containerBuilder));
    }

    /**
     * Tests getAssetsRelativeDirectory()
     *
     * @return void
     */
    public function testGetAssetsRelativeDirectory(): void {

        $obj = new WBWEDMBundle();

        $this->assertEquals(AssetsProviderInterface::ASSETS_RELATIVE_DIRECTORY, $obj->getAssetsRelativeDirectory());
    }

    /**
     * Tests getContainerExtension()
     *
     * @return void
     */
    public function testGetContainerExtension(): void {

        $obj = new WBWEDMBundle();

        $this->assertInstanceOf(WBWEDMExtension::class, $obj->getContainerExtension());
    }

    /**
     * Tests listAssets()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testListAssets(): void {

        $assets = realpath(__DIR__ . "/../Resources/assets");

        $res = AssetsHelper::listAssets($assets);
        $this->assertCount(1, $res);

        $i = -1;

        $this->assertRegexp("/" . preg_quote("dropzone-5.9.3.zip") . "$/", $res[++$i]);
    }
}
