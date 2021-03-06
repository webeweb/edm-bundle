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
use WBW\Bundle\EDMBundle\Translation\TranslationInterface;

/**
 * Upload document form type test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 */
class UploadDocumentFormTypeTest extends AbstractFormTypeTestCase {

    /**
     * Tests the buildForm() method.
     *
     * @return void
     */
    public function testBuildForm(): void {

        $obj = new UploadDocumentFormType();

        $this->assertNull($obj->buildForm($this->formBuilder, [
            "disabled" => false,
        ]));

        $this->assertCount(1, $this->childs);

        $this->assertArrayHasKey("uploadedFile", $this->childs);
        $this->assertEquals(FileType::class, $this->childs["uploadedFile"]["type"]);
        $this->assertEquals("label.file", $this->childs["uploadedFile"]["options"]["label"]);
        $this->assertEquals(false, $this->childs["uploadedFile"]["options"]["disabled"]);
        $this->assertEquals(false, $this->childs["uploadedFile"]["options"]["required"]);
    }

    /**
     * Tests the configureOptions() method.
     *
     * @return void
     */
    public function testConfigureOptions(): void {

        $obj = new UploadDocumentFormType();

        $this->assertNull($obj->configureOptions($this->resolver));

        $res = [
            "csrf_protection"    => true,
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

        $obj = new UploadDocumentFormType();

        $this->assertEquals(WBWEDMExtension::EXTENSION_ALIAS . "_document_upload", $obj->getBlockPrefix());
    }

    /**
     * Tests the onSubmit() method.
     *
     * @return void
     */
    public function testOnSubmit(): void {

        // Set a Document mock.
        $document = new Document();
        $document->setParent(new Document());
        $document->setUploadedFile(new UploadedFile(getcwd() . "/phpunit.xml.dist", "phpunit.xml.dist"));

        // Set a Form event mock.
        $formEvent = new FormEvent($this->form, $document);

        $obj = new UploadDocumentFormType();

        $this->assertSame($formEvent, $obj->onSubmit($formEvent));

        $this->assertEquals("dist", $document->getExtension());
        $this->assertEquals("application/octet-stream", $document->getMimeType());
        $this->assertEquals("phpunit.xml", $document->getName());
        $this->assertGreaterThan(0, $document->getSize());

        $this->assertEquals("ebceb28ae3c77b72744479f5483869ac", $document->getHashMd5());
        $this->assertEquals("c25c1a4939e2f2a3ffcae26fc2edc2624ad1cf6c", $document->getHashSha1());
        $this->assertEquals("b4600fd17390fb86efff1dc0628702a2168e6d4252f86ef42eb802e7acf67fb3", $document->getHashSha256());
    }
}
