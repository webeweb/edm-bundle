<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Test kernel.
 *
 * @author webeweb <https://github.com/webeweb/>
 */
class TestKernel extends Kernel {

    /**
     * Get the bundle directory.
     *
     * @return string Returns the bundle directory.
     */
    protected function getBundleDir() {
        return getcwd();
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir() {
        return $this->getBundleDir() . "/Tests/Fixtures/app/var/cache";
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir() {
        return $this->getBundleDir() . "/Tests/Fixtures/app/var/logs";
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles() {
        $bundles = [
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new WBW\Bundle\BootstrapBundle\BootstrapBundle(),
            new WBW\Bundle\EDMBundle\EDMBundle(),
        ];
        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader) {
        $loader->load($this->getBundleDir() . "/Tests/Fixtures/app/config/config_test.yml");
    }

}
