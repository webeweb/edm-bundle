<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\DependencyInjection\Compiler;

use Doctrine\Common\Persistence\ObjectManager;
use WBW\Bundle\EDMBundle\DependencyInjection\Compiler\EDMCompilerPass;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Manager\StorageManagerInterface;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;

/**
 * EDM compiler pass test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\DependencyInjection\Compiler
 */
class EDMCompilerPassTest extends AbstractFrameworkTestCase {

    /**
     * Object manager.
     *
     * @var ObjectManager
     */
    private $em;

    /**
     * Storage manager.
     *
     * @var StorageManagerInterface
     */
    private $storageManager;

    /**
     * Storage provider.
     *
     * @var StorageProviderInterface
     */
    private $storageProvider;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set an Object manager mock.
        $this->em = $this->getMockBuilder(ObjectManager::class)->getMock();

        // Set a Storage manager mock.
        $this->storageManager = new StorageManager($this->em);

        // Set a Storage provider mock.
        $this->storageProvider = $this->getMockBuilder(StorageProviderInterface::class)->getMock();
    }

    /**
     * Tests the process() method.
     *
     * @return void
     */
    public function testProcess() {

        $obj = new EDMCompilerPass();

        $obj->process($this->containerBuilder);
        $this->assertFalse($this->containerBuilder->hasDefinition(StorageManager::SERVICE_NAME));

        $this->containerBuilder->register(StorageManager::SERVICE_NAME, $this->storageManager);
        $obj->process($this->containerBuilder);
        $this->assertTrue($this->containerBuilder->hasDefinition(StorageManagerInterface::SERVICE_NAME));
        $this->assertFalse($this->containerBuilder->getDefinition(StorageManagerInterface::SERVICE_NAME)->hasMethodCall("registerProvider"));

        $this->containerBuilder->register("storage.provider.test", $this->storageProvider)->addTag(StorageProviderInterface::TAG_NAME);
        $this->assertTrue($this->containerBuilder->hasDefinition(StorageManagerInterface::SERVICE_NAME));
        $this->assertFalse($this->containerBuilder->getDefinition(StorageManagerInterface::SERVICE_NAME)->hasMethodCall("registerProvider"));
        $this->assertTrue($this->containerBuilder->hasDefinition("storage.provider.test"));
        $this->assertTrue($this->containerBuilder->getDefinition("storage.provider.test")->hasTag(StorageProviderInterface::TAG_NAME));

        $obj->process($this->containerBuilder);
        $this->assertTrue($this->containerBuilder->hasDefinition(StorageManagerInterface::SERVICE_NAME));
        $this->assertTrue($this->containerBuilder->getDefinition(StorageManagerInterface::SERVICE_NAME)->hasMethodCall("registerProvider"));
        $this->assertTrue($this->containerBuilder->hasDefinition("storage.provider.test"));
        $this->assertTrue($this->containerBuilder->getDefinition("storage.provider.test")->hasTag(StorageProviderInterface::TAG_NAME));
    }

}
