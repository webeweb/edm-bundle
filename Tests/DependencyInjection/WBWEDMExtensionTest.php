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

use Exception;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use WBW\Bundle\BootstrapBundle\Twig\Extension\CSS\ButtonTwigExtension;
use WBW\Bundle\EDMBundle\DependencyInjection\Configuration;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\EventListener\DocumentEventListener;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Provider\DataTables\DocumentDataTablesProvider;
use WBW\Bundle\EDMBundle\Provider\DocumentIconProvider;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Twig\Extension\EDMTwigExtension;

/**
 * EDM extension test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\DependencyInjection
 */
class WBWEDMExtensionTest extends AbstractTestCase {

    /**
     * Configs.
     *
     * @var array
     */
    private $configs;

    /**
     * {@inheritDoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set a configs array mock.
        $this->configs = [
            WBWEDMExtension::EXTENSION_ALIAS => [
                "datatables"      => true,
                "event_listeners" => true,
                "providers"       => true,
                "twig"            => true,
            ],
        ];
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $this->assertEquals("wbw_edm", WBWEDMExtension::EXTENSION_ALIAS);
    }

    /**
     * Tests the getAlias() method.
     *
     * @return void
     */
    public function testGetAlias() {

        $obj = new WBWEDMExtension();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS, $obj->getAlias());
    }

    /**
     * Tests the getConfiguration() method.
     *
     * @return void
     */
    public function testGetConfiguration() {

        $obj = new WBWEDMExtension();

        $this->assertInstanceOf(Configuration::class, $obj->getConfiguration([], $this->containerBuilder));
    }

    /**
     * Tests the load() method.
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testLoad() {

        // Set a Button Twig extension mock.
        $this->containerBuilder->set(ButtonTwigExtension::SERVICE_NAME, new ButtonTwigExtension($this->twigEnvironment));

        $obj = new WBWEDMExtension();

        $this->assertNull($obj->load($this->configs, $this->containerBuilder));

        // DataTables
        $this->assertInstanceOf(DocumentDataTablesProvider::class, $this->containerBuilder->get(DocumentDataTablesProvider::SERVICE_NAME));

        // Event listeners
        $this->assertInstanceOf(DocumentEventListener::class, $this->containerBuilder->get(DocumentEventListener::SERVICE_NAME));

        // Managers
        $this->assertInstanceOf(StorageManager::class, $this->containerBuilder->get(StorageManager::SERVICE_NAME));

        // Providers
        $this->assertInstanceOf(DocumentIconProvider::class, $this->containerBuilder->get(DocumentIconProvider::SERVICE_NAME));

        // Twig extensions.
        $this->assertInstanceOf(EDMTwigExtension::class, $this->containerBuilder->get(EDMTwigExtension::SERVICE_NAME));
    }

    /**
     * Tests the load() method.
     *
     * @return void
     */
    public function testLoadWithoutDataTables() {

        // Set the configs mock.
        $this->configs[WBWEDMExtension::EXTENSION_ALIAS]["datatables"] = false;

        $obj = new WBWEDMExtension();

        $this->assertNull($obj->load($this->configs, $this->containerBuilder));

        try {

            $this->containerBuilder->get(DocumentDataTablesProvider::SERVICE_NAME);
        } catch (Exception $ex) {

            $this->assertInstanceOf(ServiceNotFoundException::class, $ex);
            $this->assertContains(DocumentDataTablesProvider::SERVICE_NAME, $ex->getMessage());
        }
    }

    /**
     * Tests the load() method.
     *
     * @return void
     */
    public function testLoadWithoutEventListeners() {

        // Set the configs mock.
        $this->configs[WBWEDMExtension::EXTENSION_ALIAS]["event_listeners"] = false;

        $obj = new WBWEDMExtension();

        $this->assertNull($obj->load($this->configs, $this->containerBuilder));

        try {

            $this->containerBuilder->get(DocumentEventListener::SERVICE_NAME);
        } catch (Exception $ex) {

            $this->assertInstanceOf(ServiceNotFoundException::class, $ex);
            $this->assertContains(DocumentEventListener::SERVICE_NAME, $ex->getMessage());
        }
    }

    /**
     * Tests the load() method.
     *
     * @return void
     */
    public function testLoadWithoutProviders() {

        // Set the configs mock.
        $this->configs[WBWEDMExtension::EXTENSION_ALIAS]["providers"] = false;

        $obj = new WBWEDMExtension();

        $this->assertNull($obj->load($this->configs, $this->containerBuilder));

        try {

            $this->containerBuilder->get(DocumentIconProvider::SERVICE_NAME);
        } catch (Exception $ex) {

            $this->assertInstanceOf(ServiceNotFoundException::class, $ex);
            $this->assertContains(DocumentIconProvider::SERVICE_NAME, $ex->getMessage());
        }
    }

    /**
     * Tests the load() method.
     *
     * @return void
     */
    public function testLoadWithoutTwig() {

        // Set the configs mock.
        $this->configs[WBWEDMExtension::EXTENSION_ALIAS]["twig"] = false;

        $obj = new WBWEDMExtension();

        $this->assertNull($obj->load($this->configs, $this->containerBuilder));

        try {

            $this->containerBuilder->get(EDMTwigExtension::SERVICE_NAME);
        } catch (Exception $ex) {

            $this->assertInstanceOf(ServiceNotFoundException::class, $ex);
            $this->assertContains(EDMTwigExtension::SERVICE_NAME, $ex->getMessage());
        }
    }
}
