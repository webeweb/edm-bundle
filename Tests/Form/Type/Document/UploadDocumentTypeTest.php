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

use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\Document\UploadDocumentType;
use WBW\Bundle\EDMBundle\Tests\Form\Type\AbstractFormTypeTest;

/**
 * Upload document type test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 */
class UploadDocumentTypeTest extends AbstractFormTypeTest {

    /**
     * Tests the buildForm() method.
     *
     * @return void
     */
    public function testBuildForm() {

        $obj = new UploadDocumentType();

        $this->assertNull($obj->buildForm($this->formBuilder, []));
    }

    /**
     * Tests the configureOptions() method.
     *
     * @return void
     */
    public function testConfigureOptions() {

        $obj = new UploadDocumentType();

        $this->assertNull($obj->configureOptions($this->resolver));
    }

    /**
     * Tests getBlockPrefix() method.
     *
     * @return void
     */
    public function testGetBlockPrefix() {

        $obj = new UploadDocumentType();

        $this->assertEquals("edmbundle_upload_document", $obj->getBlockPrefix());
    }

    /**
     * Tests the onSubmit() method.
     *
     * @return void
     */
    public function testOnSubmit() {

        $obj = new UploadDocumentType();

        $arg = new FormEvent($this->form, new Document());
        $arg->getData()->setUpload(new UploadedFile(getcwd() . "/phpunit.xml.dist", "phpunit.xml.dist"));

        $this->assertNull($obj->onSubmit($arg));
        $this->assertEquals("phpunit.xml", $arg->getData()->getName());
    }

}
