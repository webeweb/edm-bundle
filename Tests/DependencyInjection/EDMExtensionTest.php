<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\DependencyInjection;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use WBW\Bundle\EDMBundle\DependencyInjection\EDMExtension;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Twig\Extension\EDMTwigExtension;

/**
 * EDM extension test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\DependencyInjection
 * @final
 */
final class EDMExtensionTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests the load() method.
	 *
	 * @return void
	 */
	public function testLoad() {

		// Set the mocks.
		$kernel		 = $this->getMockBuilder(KernelInterface::class)->getMock();
		$manager	 = $this->getMockBuilder(ObjectManager::class)->getMock();
		$router		 = $this->getMockBuilder(RouterInterface::class)->getMock();
		$translator	 = $this->getMockBuilder(TranslatorInterface::class)->getMock();

		// We set a container builder with only the necessary.
		$container = new ContainerBuilder(new ParameterBag(["kernel.environment" => "dev", "kernel.root_dir" => getcwd(), "webeweb.bundle.edmbundle.directory" => getcwd()]));
		$container->set("doctrine.orm.entity_manager", $manager);
		$container->set("kernel", $kernel);
		$container->set("router", $router);
		$container->set("translator", $translator);

		$obj = new EDMExtension();
		$obj->load([], $container);
		$this->assertInstanceOf(StorageManager::class, $container->get(StorageManager::SERVICE_NAME));
		$this->assertInstanceOf(EDMTwigExtension::class, $container->get(EDMTwigExtension::SERVICE_NAME));
	}

}
