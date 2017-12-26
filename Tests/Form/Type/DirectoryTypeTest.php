<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Form\Type;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Form\Type\DirectoryType;

/**
 * Directory type test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Test\Form\Type
 * @final
 */
final class DirectoryTypeTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests the buildForm() method.
	 *
	 * @return void
	 */
	public function testBuildForm() {

		// Set the mocks.
		$formBuilder = $this->getMockBuilder(FormBuilderInterface::class)->getMock();
		$formBuilder->expects($this->any())->method("add")->willReturnCallback(function($child, $type = null, array $options = array()) use($formBuilder) {
			return $formBuilder;
		});

		$obj = new DirectoryType();

		$obj->buildForm($formBuilder, ["entity.parent" => []]);
	}

	/**
	 * Tests the configureOptions() method.
	 *
	 * @return void
	 */
	public function testConfigureOptions() {

		$obj = new DirectoryType();
		$arg = new OptionsResolver();

		$obj->configureOptions($arg);
		$this->assertEquals(true, $arg->hasDefault("data_class"));
		$this->assertEquals(true, $arg->hasDefault("translation_domain"));
	}

	/**
	 * Tests the getBlockPrefix() method.
	 *
	 * @return void
	 */
	public function testGetBlockPrefix() {

		$obj = new DirectoryType();

		$this->assertEquals("edmbundle_directory", $obj->getBlockPrefix());
	}

}
