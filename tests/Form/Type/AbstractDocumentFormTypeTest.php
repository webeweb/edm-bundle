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

use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Form\Type\TestDocumentFormType;

/**
 * Abstract document form type test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Form\Type
 */
class AbstractDocumentFormTypeTest extends AbstractTestCase {

    /**
     * Test getBlockPrefix()
     *
     * @return void.
     */
    public function testGetBlockPrefix(): void {

        $obj = new TestDocumentFormType();

        $this->assertEquals("wbw_edm_document", $obj->getBlockPrefix());
    }
}
