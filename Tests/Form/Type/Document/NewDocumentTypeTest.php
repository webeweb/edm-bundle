<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Form\Type\Document;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Form\Type\Document\NewDocumentType;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;

/**
 * New document type test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 * @final
 */
final class NewDocumentTypeTest extends AbstractFrameworkTestCase {

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
     * {@onheritdoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set a Form builder mock.
        $this->formBuilder = $this->getMockBuilder(FormBuilderInterface::class)->getMock();
        $this->formBuilder->expects($this->any())->method("add")->willReturn($this->formBuilder);
        $this->formBuilder->expects($this->any())->method("addEventListener")->willReturn($this->formBuilder);
        $this->formBuilder->expects($this->any())->method("addModelTransformer")->willReturn($this->formBuilder);
        $this->formBuilder->expects($this->any())->method("get")->willReturn($this->formBuilder);

        // Set an Options resolver mock.
        $this->resolver = $this->getMockBuilder(OptionsResolver::class)->getMock();
    }

    /**
     * Tests the buildForm() method.
     *
     * @return void
     */
    public function testBuildForm() {

        $obj = new NewDocumentType($this->objectManager);

        $obj->buildForm($this->formBuilder, []);
    }

    /**
     * Tests the configureOptions() method.
     *
     * @return void
     */
    public function testConfigureOptions() {

        $obj = new NewDocumentType($this->objectManager);

        $obj->configureOptions($this->resolver);
    }

    /**
     * Tests getBlockPrefix() method.
     *
     * @return void
     */
    public function testGetBlockPrefix() {

        $obj = new NewDocumentType($this->objectManager);

        $this->assertEquals("edmbundle_new_document", $obj->getBlockPrefix());
    }

}
