<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use WBW\Bundle\CoreBundle\Config\ConfigurationHelper;

/**
 * Configuration.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder {

        $treeBuilder = new TreeBuilder(WBWEDMExtension::EXTENSION_ALIAS);

        $rootNode = ConfigurationHelper::getRootNode($treeBuilder, WBWEDMExtension::EXTENSION_ALIAS);
        $rootNode
            ->children()
                ->booleanNode("commands")->defaultTrue()->info("Load commands")->end()
                ->booleanNode("datatables")->defaultTrue()->info("Load DataTables providers")->end()
                ->booleanNode("event_listeners")->defaultTrue()->info("Load event listeners")->end()
                ->booleanNode("twig")->defaultTrue()->info("Load Twig extensions")->end()
            ->end();

        return $treeBuilder;
    }
}
