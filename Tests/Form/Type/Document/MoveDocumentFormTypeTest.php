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

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use WBW\Bundle\CoreBundle\Tests\AbstractFormTypeTestCase;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\Document\MoveDocumentFormType;
use WBW\Bundle\EDMBundle\Translation\TranslatorInterface;

/**
 * Move document type test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 */
class MoveDocumentFormTypeTest extends AbstractFormTypeTestCase {

    /**
     * Tests buildForm()
     *
     * @return void
     */
    public function testBuildForm(): void {

        $obj = new MoveDocumentFormType();

        $this->assertNull($obj->buildForm($this->formBuilder, [
            "disabled"      => false,
            "entity.parent" => [],
        ]));

        $this->assertCount(1, $this->children);

        $this->assertArrayHasKey("parent", $this->children);
        $this->assertEquals(EntityType::class, $this->children["parent"]["type"]);
        $this->assertEquals(false, $this->children["parent"]["options"]["disabled"]);
        $this->assertEquals("label.parent", $this->children["parent"]["options"]["label"]);
        $this->assertEquals(false, $this->children["parent"]["options"]["required"]);
    }

    /**
     * Tests configureOptions()
     *
     * @return void
     */
    public function testConfigureOptions(): void {

        $obj = new MoveDocumentFormType();

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

        $obj = new MoveDocumentFormType();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS . "_document_move", $obj->getBlockPrefix());
    }

    /**
     * Tests onPreSetData()
     *
     * @return void
     */
    public function testOnPreSetData(): void {

        // Set a Document mock.
        $document = new Document();
        $document->setParent(new Document());

        // Set a Form event mock.
        $formEvent = new FormEvent($this->form, $document);

        $obj = new MoveDocumentFormType();

        $this->assertSame($formEvent, $obj->onPreSetData($formEvent));
        $this->assertSame($document->getParent(), $document->getSavedParent());
    }
}
