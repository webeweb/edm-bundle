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

use WBW\Bundle\EDMBundle\Form\Type\Document\NewDocumentType;
use WBW\Bundle\EDMBundle\Tests\Form\Type\AbstractFormTypeTest;

/**
 * New document type test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type\Document
 * @final
 */
final class NewDocumentTypeTest extends AbstractFormTypeTest {

    /**
     * Tests the buildForm() method.
     *
     * @return void
     */
    public function testBuildForm() {

        $obj = new NewDocumentType($this->objectManager);

        $this->assertNull($obj->buildForm($this->formBuilder, []));
    }

    /**
     * Tests the configureOptions() method.
     *
     * @return void
     */
    public function testConfigureOptions() {

        $obj = new NewDocumentType($this->objectManager);

        $this->assertNull($obj->configureOptions($this->resolver));
    }

    /**
     * Tests getBlockPrefix() method.
     *
     * @return void
     */
    public function testGetBlockPrefix() {

        $obj = new NewDocumentType($this->objectManager);

        $this->assertEquals("edmbundle_new_document", $obj->getBlockPrefix());
    }

}
