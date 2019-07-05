<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;

/**
 * Storage provider compiler pass.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\DependencyInjection\Compiler
 */
class StorageProviderCompilerPass implements CompilerPassInterface {

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container) {

        if (false === $container->has(StorageManager::SERVICE_NAME)) {
            return;
        }

        $manager = $container->findDefinition(StorageManager::SERVICE_NAME);

        $providers = $container->findTaggedServiceIds(StorageProviderInterface::TAG_NAME);
        foreach ($providers as $id => $tag) {
            $manager->addMethodCall("addProvider", [new Reference($id)]);
        }
    }
}
