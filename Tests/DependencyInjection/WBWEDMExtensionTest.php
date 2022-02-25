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
use WBW\Bundle\CoreBundle\EventListener\KernelEventListener;
use WBW\Bundle\EDMBundle\Command\ListStorageProviderCommand;
use WBW\Bundle\EDMBundle\DependencyInjection\Configuration;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\EventListener\DocumentEventListener;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Provider\DataTables\DocumentDataTablesProvider;
use WBW\Bundle\EDMBundle\Provider\DocumentIconProvider;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * EDM extension test.
 *
 * @author webeweb <https://github.com/webeweb>
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
    protected function setUp(): void {
        parent::setUp();

        // Set a configs array mock.
        $this->configs = [
            WBWEDMExtension::EXTENSION_ALIAS => [
                "commands"        => true,
                "datatables"      => true,
                "event_listeners" => true,
                "twig"            => true,
            ],
        ];

        // Set a Button Twig extension mock.
        $this->containerBuilder->set(ButtonTwigExtension::SERVICE_NAME, new ButtonTwigExtension($this->twigEnvironment));

        // Set a Kernel event listener mock.
        $this->containerBuilder->set(KernelEventListener::SERVICE_NAME, $this->kernelEventListener);
    }

    /**
     * Tests getAlias()
     *
     * @return void
     */
    public function testGetAlias(): void {

        $obj = new WBWEDMExtension();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS, $obj->getAlias());
    }

    /**
     * Tests getConfiguration()
     *
     * @return void
     */
    public function testGetConfiguration(): void {

        $obj = new WBWEDMExtension();

        $this->assertInstanceOf(Configuration::class, $obj->getConfiguration([], $this->containerBuilder));
    }

    /**
     * Tests load()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testLoad(): void {

        $obj = new WBWEDMExtension();

        $this->assertNull($obj->load($this->configs, $this->containerBuilder));

        // Commands
        $this->assertInstanceOf(ListStorageProviderCommand::class, $this->containerBuilder->get(ListStorageProviderCommand::SERVICE_NAME));

        // DataTables providers
        $this->assertInstanceOf(DocumentDataTablesProvider::class, $this->containerBuilder->get(DocumentDataTablesProvider::SERVICE_NAME));

        // Event listeners
        $this->assertInstanceOf(DocumentEventListener::class, $this->containerBuilder->get(DocumentEventListener::SERVICE_NAME));

        // Managers
        $this->assertInstanceOf(StorageManager::class, $this->containerBuilder->get(StorageManager::SERVICE_NAME));

        // Providers
        $this->assertInstanceOf(DocumentIconProvider::class, $this->containerBuilder->get(DocumentIconProvider::SERVICE_NAME));
    }

    /**
     * Tests load()
     *
     * @return void
     */
    public function testLoadWithoutCommands(): void {

        // Set the configs mock.
        $this->configs[WBWEDMExtension::EXTENSION_ALIAS]["commands"] = false;

        $obj = new WBWEDMExtension();

        $this->assertNull($obj->load($this->configs, $this->containerBuilder));

        try {

            $this->containerBuilder->get(ListStorageProviderCommand::SERVICE_NAME);
        } catch (Exception $ex) {

            $this->assertInstanceOf(ServiceNotFoundException::class, $ex);
            $this->assertStringContainsString(ListStorageProviderCommand::SERVICE_NAME, $ex->getMessage());
        }
    }

    /**
     * Tests load()
     *
     * @return void
     */
    public function testLoadWithoutDataTables(): void {

        // Set the configs mock.
        $this->configs[WBWEDMExtension::EXTENSION_ALIAS]["datatables"] = false;

        $obj = new WBWEDMExtension();

        $this->assertNull($obj->load($this->configs, $this->containerBuilder));

        try {

            $this->containerBuilder->get(DocumentDataTablesProvider::SERVICE_NAME);
        } catch (Exception $ex) {

            $this->assertInstanceOf(ServiceNotFoundException::class, $ex);
            $this->assertStringContainsString(DocumentDataTablesProvider::SERVICE_NAME, $ex->getMessage());
        }
    }

    /**
     * Tests load()
     *
     * @return void
     */
    public function testLoadWithoutEventListeners(): void {

        // Set the configs mock.
        $this->configs[WBWEDMExtension::EXTENSION_ALIAS]["event_listeners"] = false;

        $obj = new WBWEDMExtension();

        $this->assertNull($obj->load($this->configs, $this->containerBuilder));

        try {

            $this->containerBuilder->get(DocumentEventListener::SERVICE_NAME);
        } catch (Exception $ex) {

            $this->assertInstanceOf(ServiceNotFoundException::class, $ex);
            $this->assertStringContainsString(DocumentEventListener::SERVICE_NAME, $ex->getMessage());
        }
    }

    /**
     * Tests __construct()
     *
     * @return void
     */
    public function test__construct(): void {

        $this->assertEquals("wbw_edm", WBWEDMExtension::EXTENSION_ALIAS);
    }
}
