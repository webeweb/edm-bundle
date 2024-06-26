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

namespace WBW\Bundle\EDMBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use WBW\Bundle\CommonBundle\Provider\AssetsProviderInterface;
use WBW\Bundle\EDMBundle\DependencyInjection\Compiler\StorageProviderCompilerPass;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;

/**
 * EDM bundle.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle
 */
class WBWEDMBundle extends Bundle implements AssetsProviderInterface {

    /**
     * Translation domain.
     *
     * @var string
     */
    public const TRANSLATION_DOMAIN = "WBWEDMBundle";

    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container): void {
        $container->addCompilerPass(new StorageProviderCompilerPass());
    }

    /**
     * {@inheritDoc}
     */
    public function getAssetsRelativeDirectory(): string {
        return self::ASSETS_RELATIVE_DIRECTORY;
    }

    /**
     * {@inheritDoc}
     */
    public function getContainerExtension(): Extension {
        return new WBWEDMExtension();
    }

    /**
     * Get the translation domain.
     *
     * @return string Returns the translation domain.
     */
    public static function getTranslationDomain(): string {
        return self::TRANSLATION_DOMAIN;
    }
}
