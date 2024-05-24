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
use WBW\Bundle\EDMBundle\WBWEDMBundle;

/**
 * Upload document form type test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 */
class UploadDocumentFormTypeTest extends AbstractFormTypeTestCase {

    /**
     * Test buildForm()
     *
     * @return void
     */
    public function testBuildForm(): void {

        $obj = new UploadDocumentFormType();

        $obj->buildForm($this->formBuilder, [
            "disabled" => false,
        ]);

        $this->assertCount(1, $this->children);

        $this->assertArrayHasKey("uploadedFile", $this->children);
        $this->assertEquals(FileType::class, $this->children["uploadedFile"]["type"]);
        $this->assertFalse( $this->children["uploadedFile"]["options"]["disabled"]);
        $this->assertEquals("label.file", $this->children["uploadedFile"]["options"]["label"]);
        $this->assertFalse( $this->children["uploadedFile"]["options"]["required"]);
    }

    /**
     * Test configureOptions()
     *
     * @return void
     */
    public function testConfigureOptions(): void {

        $obj = new UploadDocumentFormType();

        $obj->configureOptions($this->resolver);

        $res = [
            "csrf_protection"    => true,
            "data_class"         => Document::class,
            "translation_domain" => WBWEDMBundle::getTranslationDomain(),
            "validation_groups"  => "upload",
        ];
        $this->assertEquals($res, $this->defaults);
    }

    /**
     * Test getBlockPrefix() method.
     *
     * @return void
     */
    public function testGetBlockPrefix(): void {

        $obj = new UploadDocumentFormType();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS . "_document_upload", $obj->getBlockPrefix());
    }

    /**
     * Test onSubmit()
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

        $this->assertEquals("5d0573de0594def9d3c3e45081539535", $document->getHashMd5());
        $this->assertEquals("943a798cc93e4f0b652bd28a5e6e2105067a811b", $document->getHashSha1());
        $this->assertEquals("a013a556625b6a1371a79bdd1da2b1e9a65e39f05d04741f4cef738aac1165ed", $document->getHashSha256());
    }
}
