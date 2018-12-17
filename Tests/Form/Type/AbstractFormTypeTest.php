<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;

/**
 * Abstract form type test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type
 * @abstract
 */
abstract class AbstractFormTypeTest extends AbstractFrameworkTestCase {

    /**
     * Form
     *
     * @var FormInterface
     */
    protected $form;

    /**
     * Form builder.
     *
     * @var FormBuilderInterface
     */
    protected $formBuilder;

    /**
     * Options resolver.
     *
     * @var OptionsResolver
     */
    protected $resolver;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set a Form mock.
        $this->form = $this->getMockBuilder(FormInterface::class)->getMock();

        // Set a Form builder mock.
        $this->formBuilder = $this->getMockBuilder(FormBuilderInterface::class)->getMock();
        $this->formBuilder->expects($this->any())->method("add")->willReturn($this->formBuilder);
        $this->formBuilder->expects($this->any())->method("addEventListener")->willReturn($this->formBuilder);
        $this->formBuilder->expects($this->any())->method("addModelTransformer")->willReturn($this->formBuilder);
        $this->formBuilder->expects($this->any())->method("get")->willReturn($this->formBuilder);

        // Set an Options resolver mock.
        $this->resolver = $this->getMockBuilder(OptionsResolver::class)->getMock();
    }

}
