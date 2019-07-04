<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\DependencyInjection;

use WBW\Bundle\EDMBundle\DependencyInjection\EDMExtension;
use WBW\Bundle\EDMBundle\Manager\StorageManagerInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;
use WBW\Bundle\EDMBundle\Twig\Extension\EDMTwigExtension;

/**
 * EDM extension test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\DependencyInjection
 */
class EDMExtensionTest extends AbstractFrameworkTestCase {

    /**
     * Tests the load() method.
     *
     * @return void
     */
    public function testLoad() {

        $obj = new EDMExtension();

        $this->assertNull($obj->load([], $this->containerBuilder));

        // Managers
        $this->assertInstanceOf(StorageManagerInterface::class, $this->containerBuilder->get(StorageManagerInterface::SERVICE_NAME));

        // Twig extensions
        $this->assertInstanceOf(EDMTwigExtension::class, $this->containerBuilder->get(EDMTwigExtension::SERVICE_NAME));
    }

}
