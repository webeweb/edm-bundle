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
     * Tests process()
     *
     * @return void
     */
    public function testProcess(): void {

        $obj = new StorageProviderCompilerPass();

        $obj->process($this->containerBuilder);
        $this->assertFalse($this->containerBuilder->hasDefinition(StorageManager::SERVICE_NAME));

        $this->containerBuilder->register(StorageManager::SERVICE_NAME, $this->storageManager);
        $obj->process($this->containerBuilder);
        $this->assertTrue($this->containerBuilder->hasDefinition(StorageManager::SERVICE_NAME));
        $this->assertFalse($this->containerBuilder->getDefinition(StorageManager::SERVICE_NAME)->hasMethodCall("addProvider"));

        $this->containerBuilder->register("storage.provider.test", $this->storageProvider)->addTag(StorageProviderInterface::TAG_NAME);
        $this->assertTrue($this->containerBuilder->hasDefinition(StorageManager::SERVICE_NAME));
        $this->assertFalse($this->containerBuilder->getDefinition(StorageManager::SERVICE_NAME)->hasMethodCall("addProvider"));
        $this->assertTrue($this->containerBuilder->hasDefinition("storage.provider.test"));
        $this->assertTrue($this->containerBuilder->getDefinition("storage.provider.test")->hasTag(StorageProviderInterface::TAG_NAME));

        $obj->process($this->containerBuilder);
        $this->assertTrue($this->containerBuilder->hasDefinition(StorageManager::SERVICE_NAME));
        $this->assertTrue($this->containerBuilder->getDefinition(StorageManager::SERVICE_NAME)->hasMethodCall("addProvider"));
        $this->assertTrue($this->containerBuilder->hasDefinition("storage.provider.test"));
        $this->assertTrue($this->containerBuilder->getDefinition("storage.provider.test")->hasTag(StorageProviderInterface::TAG_NAME));
    }
}
