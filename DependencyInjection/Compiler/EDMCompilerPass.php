<?php

/**
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
use WBW\Bundle\EDMBundle\Manager\StorageManagerInterface;

/**
 * EDM compiler pass.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\DependencyInjection\Compiler
 * @final
 */
final class EDMCompilerPass implements CompilerPassInterface {

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container) {

        // Check if the storage manager is defined.
        if (false === $container->has(StorageManagerInterface::SERVICE_NAME)) {
            return;
        }

        // Get the storage manager.
        $manager = $container->findDefinition(StorageManagerInterface::SERVICE_NAME);

        // Find all service IDs with the edm.provider tag.
        $providers = $container->findTaggedServiceIds("edm.storage.provider");

        // Register each provider.
        foreach ($providers as $id => $tag) {
            $manager->addMethodCall("registerProvider", [new Reference($id)]);
        }
    }

}
