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

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\CoreBundle\Tests\AbstractFormTypeTestCase;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\Document\UploadDocumentFormType;
use WBW\Bundle\EDMBundle\Translation\TranslatorInterface;

/**
 * Upload document form type test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 */
class UploadDocumentFormTypeTest extends AbstractFormTypeTestCase {

    /**
     * Tests buildForm()
     *
     * @return void
     */
    public function testBuildForm(): void {

        $obj = new UploadDocumentFormType();

        $this->assertNull($obj->buildForm($this->formBuilder, [
            "disabled" => false,
        ]));

        $this->assertCount(1, $this->children);

        $this->assertArrayHasKey("uploadedFile", $this->children);
        $this->assertEquals(FileType::class, $this->children["uploadedFile"]["type"]);
        $this->assertEquals(false, $this->children["uploadedFile"]["options"]["disabled"]);
        $this->assertEquals("label.file", $this->children["uploadedFile"]["options"]["label"]);
        $this->assertEquals(false, $this->children["uploadedFile"]["options"]["required"]);
    }

    /**
     * Tests configureOptions()
     *
     * @return void
     */
    public function testConfigureOptions(): void {

        $obj = new UploadDocumentFormType();

        $this->assertNull($obj->configureOptions($this->resolver));

        $res = [
            "csrf_protection"    => true,
            "data_class"         => Document::class,
            "translation_domain" => TranslatorInterface::DOMAIN,
            "validation_groups"  => "upload",
        ];
        $this->assertEquals($res, $this->defaults);
    }

    /**
     * Tests getBlockPrefix() method.
     *
     * @return void
     */
    public function testGetBlockPrefix(): void {

        $obj = new UploadDocumentFormType();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS . "_document_upload", $obj->getBlockPrefix());
    }

    /**
     * Tests onSubmit()
     *
     * @return void
     */
    public function testOnSubmit(): void {

        $path = realpath(__DIR__ . "/../../../../phpunit.xml.dist");

        // Set a Document mock.
        $document = new Document();
        $document->setParent(new Document());
        $document->setUploadedFile(new UploadedFile($path, "phpunit.xml.dist"));

        // Set a Form event mock.
        $formEvent = new FormEvent($this->form, $document);

        $obj = new UploadDocumentFormType();

        $this->assertSame($formEvent, $obj->onSubmit($formEvent));

        $this->assertEquals("dist", $document->getExtension());
        $this->assertEquals("application/octet-stream", $document->getMimeType());
        $this->assertEquals("phpunit.xml", $document->getName());
        $this->assertGreaterThan(0, $document->getSize());

        $this->assertEquals("daa742aa26575d6069f8d77cd52a5ebd", $document->getHashMd5());
        $this->assertEquals("34bf735e40d53bd09aa121de8cb7034d9207fd9d", $document->getHashSha1());
        $this->assertEquals("a646a1e741211b9e7df1161ca515b834109d77b81b3439ddd7492e78cacdb01e", $document->getHashSha256());
    }
}
