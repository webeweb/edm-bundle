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
use WBW\Bundle\CoreBundle\Tests\AbstractFormTypeTestCase;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\Document\NewDirectoryFormType;
use WBW\Bundle\EDMBundle\Translation\TranslationInterface;

/**
 * New directory form type test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 */
class NewDirectoryFormTypeTest extends AbstractFormTypeTestCase {

    /**
     * Tests the buildForm() method.
     *
     * @return void
     */
    public function testBuildForm(): void {

        $obj = new NewDirectoryFormType();

        $this->assertNull($obj->buildForm($this->formBuilder, [
            "disabled" => false,
        ]));

        $this->assertCount(1, $this->childs);

        $this->assertArrayHasKey("name", $this->childs);
        $this->assertEquals(TextType::class, $this->childs["name"]["type"]);
        $this->assertEquals("label.name", $this->childs["name"]["options"]["label"]);
        $this->assertEquals(false, $this->childs["name"]["options"]["disabled"]);
        $this->assertEquals(false, $this->childs["name"]["options"]["required"]);
    }

    /**
     * Tests the configureOptions() method.
     *
     * @return void
     */
    public function testConfigureOptions(): void {

        $obj = new NewDirectoryFormType();

        $this->assertNull($obj->configureOptions($this->resolver));

        $res = [
            "data_class"         => Document::class,
            "translation_domain" => TranslationInterface::TRANSLATION_DOMAIN,
        ];
        $this->assertEquals($res, $this->defaults);
    }

    /**
     * Tests getBlockPrefix() method.
     *
     * @return void
     */
    public function testGetBlockPrefix(): void {

        $obj = new NewDirectoryFormType();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS . "_new_directory", $obj->getBlockPrefix());
    }
}
