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

namespace WBW\Bundle\EDMBundle\Tests\Fixtures;

use Doctrine;
use Symfony;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use WBW;
use WBW\Bundle\CommonBundle\Tests\DefaultKernel as BaseKernel;

/**
 * Test kernel.
 *
 * @author webeweb <https://github.com/webeweb>
 */
class TestKernel extends BaseKernel {

    /**
     * {@inheritDoc}
     * @return BundleInterface[] Returns the registered bundles.
     */
    public function registerBundles(): array {

        return [
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new WBW\Bundle\BootstrapBundle\WBWBootstrapBundle(),
            new WBW\Bundle\CommonBundle\WBWCommonBundle(),
            new WBW\Bundle\DataTablesBundle\WBWDataTablesBundle(),
            new WBW\Bundle\EDMBundle\WBWEDMBundle(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void {

        // TODO: Remove when dropping support for Symfony 5
        if (Kernel::MAJOR_VERSION < 6) {
            $loader->load($this->getProjectDir() . "/config/config_test.old.yml");
            return;
        }

        parent::registerContainerConfiguration($loader);
    }
}
