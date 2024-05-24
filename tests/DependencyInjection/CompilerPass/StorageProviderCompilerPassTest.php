<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use WBW\Bundle\EDMBundle\DependencyInjection\Compiler\StorageProviderCompilerPass;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Storage provider compiler pass test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\DependencyInjection\CompilerPass
 */
class StorageProviderCompilerPassTest extends AbstractTestCase {

    /**
     * Test process()
     *
     * @return void
     */
    public function testProcess(): void {

        // Set a Container builder mock.
        $containerBuilder = new ContainerBuilder();

        $obj = new StorageProviderCompilerPass();

        $obj->process($containerBuilder);
        $this->assertFalse($containerBuilder->hasDefinition(StorageManager::SERVICE_NAME));

        $containerBuilder->register(StorageManager::SERVICE_NAME, StorageManager::class);
        $obj->process($containerBuilder);
        $this->assertTrue($containerBuilder->hasDefinition(StorageManager::SERVICE_NAME));
        $this->assertFalse($containerBuilder->getDefinition(StorageManager::SERVICE_NAME)->hasMethodCall("addProvider"));

        $containerBuilder->register("wbw.edm.provider.test", StorageProviderInterface::class)->addTag(StorageProviderInterface::STORAGE_PROVIDER_TAG_NAME);
        $this->assertTrue($containerBuilder->hasDefinition(StorageManager::SERVICE_NAME));
        $this->assertFalse($containerBuilder->getDefinition(StorageManager::SERVICE_NAME)->hasMethodCall("addProvider"));
        $this->assertTrue($containerBuilder->hasDefinition("wbw.edm.provider.test"));
        $this->assertTrue($containerBuilder->getDefinition("wbw.edm.provider.test")->hasTag(StorageProviderInterface::STORAGE_PROVIDER_TAG_NAME));

        $obj->process($containerBuilder);
        $this->assertTrue($containerBuilder->hasDefinition(StorageManager::SERVICE_NAME));
        $this->assertTrue($containerBuilder->getDefinition(StorageManager::SERVICE_NAME)->hasMethodCall("addProvider"));
        $this->assertTrue($containerBuilder->hasDefinition("wbw.edm.provider.test"));
        $this->assertTrue($containerBuilder->getDefinition("wbw.edm.provider.test")->hasTag(StorageProviderInterface::STORAGE_PROVIDER_TAG_NAME));
    }
}
