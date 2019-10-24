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

/**
 * Move document type test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 */
class MoveDocumentFormTypeTest extends AbstractFormTypeTestCase {

    /**
     * Tests the buildForm() method.
     *
     * @return void
     */
    public function testBuildForm() {

        $obj = new MoveDocumentFormType();

        $this->assertNull($obj->buildForm($this->formBuilder, [
            "disabled"      => false,
            "entity.parent" => [],
        ]));

        $this->assertCount(1, $this->childs);

        $this->assertArrayHasKey("parent", $this->childs);
        $this->assertEquals(EntityType::class, $this->childs["parent"]["type"]);
        $this->assertEquals("label.parent", $this->childs["parent"]["options"]["label"]);
        $this->assertEquals(false, $this->childs["parent"]["options"]["disabled"]);
        $this->assertEquals(false, $this->childs["parent"]["options"]["required"]);
    }

    /**
     * Tests the configureOptions() method.
     *
     * @return void
     */
    public function testConfigureOptions() {

        $obj = new MoveDocumentFormType();

        $this->assertNull($obj->configureOptions($this->resolver));

        $res = [
            "data_class"         => Document::class,
            "translation_domain" => "WBWEDMBundle",
        ];
        $this->assertEquals($res, $this->defaults);
    }

    /**
     * Tests getBlockPrefix() method.
     *
     * @return void
     */
    public function testGetBlockPrefix() {

        $obj = new MoveDocumentFormType();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS . "_move_document", $obj->getBlockPrefix());
    }

    /**
     * Tests the onPreSetData() method.
     *
     * @return void
     */
    public function testOnPreSetData() {

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