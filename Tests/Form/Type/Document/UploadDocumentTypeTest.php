<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Form\Type\Document;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Form\Type\Document\UploadDocumentType;

/**
 * Upload document type test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 * @final
 */
final class UploadDocumentTypeTest extends PHPUnit_Framework_TestCase {

	/**
	 * Form builder.
	 *
	 * @var FormBuilderInterface
	 */
	private $formBuilder;

	/**
	 * Options resolver.
	 *
	 * @var OptionsResolver
	 */
	private $resolver;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp() {

		$this->formBuilder = $this->getMockBuilder(FormBuilderInterface::class)->getMock();
		$this->formBuilder->expects($this->any())->method("add")->willReturn($this->formBuilder);
		$this->formBuilder->expects($this->any())->method("addEventListener")->willReturn($this->formBuilder);
		$this->formBuilder->expects($this->any())->method("addModelTransformer")->willReturn($this->formBuilder);
		$this->formBuilder->expects($this->any())->method("get")->willReturn($this->formBuilder);

		$this->resolver = $this->getMockBuilder(OptionsResolver::class)->getMock();
	}

	/**
	 * Tests the buildForm() method.
	 *
	 * @return void
	 */
	public function testBuildForm() {

		$obj = new UploadDocumentType();

		$obj->buildForm($this->formBuilder, []);
	}

	/**
	 * Tests the configureOptions() method.
	 *
	 * @return void
	 */
	public function testConfigureOptions() {

		$obj = new UploadDocumentType();

		$obj->configureOptions($this->resolver);
	}

	/**
	 * Tests getBlockPrefix() method.
	 *
	 * @return void.
	 */
	public function testGetBlockPrefix() {

		$obj = new UploadDocumentType();

		$this->assertEquals("edmbundle_upload_document", $obj->getBlockPrefix());
	}

}
