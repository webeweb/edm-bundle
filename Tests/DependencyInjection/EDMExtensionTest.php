<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\DependencyInjection;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use WBW\Bundle\EDMBundle\DependencyInjection\EDMExtension;
use WBW\Bundle\EDMBundle\Manager\StorageManagerInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;
use WBW\Bundle\EDMBundle\Twig\Extension\EDMTwigExtension;

/**
 * EDM extension test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\DependencyInjection
 * @final
 */
final class EDMExtensionTest extends AbstractFrameworkTestCase {

    /**
     * Tests the load() method.
     *
     * @return void
     */
    public function testLoad() {

        // Set the mocks.
        $kernel     = $this->getMockBuilder(KernelInterface::class)->getMock();
        $manager    = $this->getMockBuilder(ObjectManager::class)->getMock();
        $router     = $this->getMockBuilder(RouterInterface::class)->getMock();
        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();

        // We set a container builder with only the necessary.
        $container = new ContainerBuilder(new ParameterBag(["kernel.environment" => "dev", "kernel.root_dir" => getcwd(), "webeweb.edm.directory" => getcwd()]));
        $container->set("doctrine.orm.entity_manager", $manager);
        $container->set("kernel", $kernel);
        $container->set("router", $router);
        $container->set("translator", $translator);

        $obj = new EDMExtension();
        $obj->load([], $container);
        $this->assertInstanceOf(EDMTwigExtension::class, $container->get(EDMTwigExtension::SERVICE_NAME));
        $this->assertInstanceOf(StorageManagerInterface::class, $container->get(StorageManagerInterface::SERVICE_NAME));
    }

}
