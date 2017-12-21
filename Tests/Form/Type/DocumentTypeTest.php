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
use WBW\Bundle\EDMBundle\Form\Type\DocumentType;

/**
 * Document type test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Test\Form\Type
 * @final
 */
final class DocumentTypeTest extends PHPUnit_Framework_TestCase {

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

		$obj = new DocumentType();

		$obj->buildForm($formBuilder, []);
	}

	/**
	 * Tests the configureOptions() method.
	 *
	 * @return void
	 */
	public function testConfigureOptions() {

		$obj = new DocumentType();
		$arg = new OptionsResolver();

		$obj->configureOptions($arg);
		$this->assertEquals(true, $arg->hasDefault("data_class"));
	}

	/**
	 * Tests the getBlockPrefix() method.
	 *
	 * @return void
	 */
	public function testGetBlockPrefix() {

		$obj = new DocumentType();

		$this->assertEquals("edmbundle_document", $obj->getBlockPrefix());
	}

}
