<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace WBW\Bundle\EDMBundle\Tests\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;
use Twig\Environment;
use WBW\Bundle\BootstrapBundle\Twig\Extension\Component\ButtonTwigExtension;
use WBW\Bundle\CommonBundle\EventListener\KernelEventListener;
use WBW\Bundle\CommonBundle\EventListener\KernelEventListenerInterface;
use WBW\Bundle\EDMBundle\Command\ListStorageProviderCommand;
use WBW\Bundle\EDMBundle\Controller\DocumentController;
use WBW\Bundle\EDMBundle\Controller\DropzoneController;
use WBW\Bundle\EDMBundle\DataTables\Provider\DocumentDataTablesProvider;
use WBW\Bundle\EDMBundle\DependencyInjection\Configuration;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\EventListener\DocumentEventListener;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
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
     * @var array<string,mixed>|null
     */
    private $configs;

    /**
     * Container builder.
     *
     * @var ContainerBuilder|null
     */
    private $containerBuilder;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void {
        parent::setUp();

        // Set a configs array mock.
        $this->configs = [
            WBWEDMExtension::EXTENSION_ALIAS => [],
        ];

        // Set an Entity manager mock.
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();

        // Set a Kernel event listener mock.
        $kernelEventListener = $this->getMockBuilder(KernelEventListenerInterface::class)->getMock();

        // Set a Logger mock.
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        // Set a Router mock.
        $router = $this->getMockBuilder(RouterInterface::class)->getMock();

        // Set a Translator mock.
        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();

        // Set a Twig environment mock.
        $twigEnvironment = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();

        // Set a Button Twig extension mock.
        $buttonTwigExtension = new ButtonTwigExtension($twigEnvironment);

        // Set a Container builder mock.
        $this->containerBuilder = new ContainerBuilder();

        $this->containerBuilder->set("doctrine.orm.entity_manager", $entityManager);
        $this->containerBuilder->set("logger", $logger);
        $this->containerBuilder->set("router", $router);
        $this->containerBuilder->set("translator", $translator);

        $this->containerBuilder->set("Psr\\Container\\ContainerInterface", $this->containerBuilder);

        $this->containerBuilder->set(ButtonTwigExtension::SERVICE_NAME, $buttonTwigExtension);
        $this->containerBuilder->set(KernelEventListener::SERVICE_NAME, $kernelEventListener);
    }

    /**
     * Test getAlias()
     *
     * @return void
     */
    public function testGetAlias(): void {

        $obj = new WBWEDMExtension();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS, $obj->getAlias());
    }

    /**
     * Test getConfiguration()
     *
     * @return void
     */
    public function testGetConfiguration(): void {

        $obj = new WBWEDMExtension();

        $this->assertInstanceOf(Configuration::class, $obj->getConfiguration([], $this->containerBuilder));
    }

    /**
     * Test load()
     *
     * @return void
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function testLoad(): void {

        $obj = new WBWEDMExtension();

        $obj->load($this->configs, $this->containerBuilder);

        // Commands
        $this->assertInstanceOf(ListStorageProviderCommand::class, $this->containerBuilder->get(ListStorageProviderCommand::SERVICE_NAME));

        // Controllers
        $this->assertInstanceOf(DocumentController::class, $this->containerBuilder->get(DocumentController::SERVICE_NAME));
        $this->assertInstanceOf(DropzoneController::class, $this->containerBuilder->get(DropzoneController::SERVICE_NAME));

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
     * Test __construct()
     *
     * @return void
     */
    public function test__construct(): void {

        $this->assertEquals("wbw_edm", WBWEDMExtension::EXTENSION_ALIAS);
    }
}
