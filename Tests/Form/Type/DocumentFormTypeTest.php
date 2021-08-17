<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use WBW\Bundle\CoreBundle\Tests\AbstractFormTypeTestCase;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\DocumentFormType;
use WBW\Bundle\EDMBundle\Translation\TranslatorInterface;

/**
 * Document form type test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type
 */
class DocumentFormTypeTest extends AbstractFormTypeTestCase {

    /**
     * Tests the buildForm() method.
     *
     * @return void
     */
    public function testBuildForm(): void {

        $obj = new DocumentFormType();

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

        $obj = new DocumentFormType();

        $this->assertNull($obj->configureOptions($this->resolver));

        $res = [
            "data_class"         => Document::class,
            "translation_domain" => TranslatorInterface::DOMAIN,
        ];
        $this->assertEquals($res, $this->defaults);
    }

    /**
     * Tests getBlockPrefix() method.
     *
     * @return void
     */
    public function testGetBlockPrefix(): void {

        $obj = new DocumentFormType();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS . "_document", $obj->getBlockPrefix());
    }
}
