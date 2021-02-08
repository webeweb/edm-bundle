<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Helper;

use WBW\Bundle\CoreBundle\Tests\Fixtures\Helper\TestAssetsHelper;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Assets helper test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Helper
 */
class AssetsHelperTest extends AbstractTestCase {

    /**
     * Directory "assets".
     *
     * @var string
     */
    private $directoryAssets;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void {
        parent::setUp();

        // Set the directories.
        $this->directoryAssets = getcwd() . "/Resources/assets";
    }

    /**
     * Tests the listAssets() method.
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testListAssets(): void {

        $res = TestAssetsHelper::listAssets($this->directoryAssets);
        $this->assertCount(1, $res);

        $this->assertRegexp("/" . preg_quote("dropzone-5.7.0.zip") . "$/", $res[0]);
    }
}
