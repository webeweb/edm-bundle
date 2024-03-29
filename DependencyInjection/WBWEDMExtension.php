<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use WBW\Bundle\CoreBundle\Config\ConfigurationHelper;

/**
 * EDM extension.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\DependencyInjection
 */
class WBWEDMExtension extends Extension {

    /**
     * Extension alias.
     *
     * @var string
     */
    const EXTENSION_ALIAS = "wbw_edm";

    /**
     * {@inheritDoc}
     */
    public function getAlias(): string {
        return self::EXTENSION_ALIAS;
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void {

        $fileLocator = new FileLocator(__DIR__ . "/../Resources/config");

        $serviceLoader = new YamlFileLoader($container, $fileLocator);
        $serviceLoader->load("controllers.yml");
        $serviceLoader->load("services.yml");

        /** @var ConfigurationInterface $configuration */
        $configuration = $this->getConfiguration($configs, $container);

        $config = $this->processConfiguration($configuration, $configs);

        $serviceLoader->load("datatables.yml");
        $serviceLoader->load("event_listeners.yml");

        if (true === $config["commands"]) {
            $serviceLoader->load("commands.yml");
        }

        if (true === $config["twig"]) {
            $serviceLoader->load("twig.yml");
        }

        ConfigurationHelper::registerContainerParameter($container, $config, $this->getAlias(), "commands");
        ConfigurationHelper::registerContainerParameter($container, $config, $this->getAlias(), "datatables");
        ConfigurationHelper::registerContainerParameter($container, $config, $this->getAlias(), "event_listeners");
        ConfigurationHelper::registerContainerParameter($container, $config, $this->getAlias(), "twig");
    }
}
