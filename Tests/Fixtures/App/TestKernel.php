<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Test kernel.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @final
 */
final class TestKernel extends Kernel {

	/**
	 * {@inheritdoc}
	 */
	public function registerBundles() {
		$bundles = [
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new WBW\Bundle\EDMBundle\EDMBundle(),
		];
		return $bundles;
	}

	/**
	 * {@inheritdoc}
	 */
	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load(__DIR__ . '/config/config_test.yml');
	}

}
