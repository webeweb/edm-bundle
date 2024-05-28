<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Form\Type\Document;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use WBW\Bundle\CommonBundle\Tests\DefaultFormTypeTestCase;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\Document\NewDirectoryFormType;
use WBW\Bundle\EDMBundle\WBWEDMBundle;

/**
 * New directory form type test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 */
class NewDirectoryFormTypeTest extends DefaultFormTypeTestCase {

    /**
     * Test buildForm()
     *
     * @return void
     */
    public function testBuildForm(): void {

        $obj = new NewDirectoryFormType();

        $obj->buildForm($this->formBuilder, [
            "disabled" => false,
        ]);

        $this->assertCount(1, $this->children);

        $this->assertArrayHasKey("name", $this->children);
        $this->assertEquals(TextType::class, $this->children["name"]["type"]);
        $this->assertFalse($this->children["name"]["options"]["disabled"]);
        $this->assertEquals("label.name", $this->children["name"]["options"]["label"]);
        $this->assertFalse($this->children["name"]["options"]["required"]);
        $this->assertTrue($this->children["name"]["options"]["trim"]);
    }

    /**
     * Test configureOptions()
     *
     * @return void
     */
    public function testConfigureOptions(): void {

        $obj = new NewDirectoryFormType();

        $obj->configureOptions($this->resolver);

        $res = [
            "data_class"         => Document::class,
            "translation_domain" => WBWEDMBundle::getTranslationDomain(),
            "validation_groups"  => "new",
        ];
        $this->assertEquals($res, $this->defaults);
    }

    /**
     * Test getBlockPrefix() method.
     *
     * @return void
     */
    public function testGetBlockPrefix(): void {

        $obj = new NewDirectoryFormType();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS . "_new_directory", $obj->getBlockPrefix());
    }
}
